#!/usr/bin/env bash
# Backup del database MySQL/MariaDB di Roosko in laravel/storage/backups (compresso, 30 giorni).
# Legge DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD da laravel/.env.
# Ripristino: gunzip -c <file>.sql.gz | mysql -h HOST -u UTENTE -p NOME_DB
#
# Il registro di carico e scarico e le trasmissioni RENTRI sono dati legali: tenere anche una copia
# fuori dal server (es. rsync notturno di storage/backups verso un altro host).
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
ENV_FILE="$ROOT/laravel/.env"
DEST="${BACKUP_DIR:-$ROOT/laravel/storage/backups}"
KEEP_DAYS="${BACKUP_KEEP_DAYS:-30}"

env_value() { { grep -E "^$1=" "$ENV_FILE" || true; } | tail -n1 | cut -d= -f2- | sed -e 's/^"//' -e 's/"$//'; }

DRIVER="$(env_value DB_CONNECTION)"
if [ "$DRIVER" != "mysql" ] && [ "$DRIVER" != "mariadb" ]; then
    echo "backup-db: DB_CONNECTION=$DRIVER, backup previsto solo per mysql/mariadb" >&2
    exit 0
fi

mkdir -p "$DEST"
STAMP="$(date +%Y%m%d-%H%M%S)"

# Due database: quello dell'applicazione e quello del modulo User (utenti, ruoli, permessi).
dump() {
    local database="$1" user="$2" password="$3" file="$DEST/$1-$STAMP.sql.gz"
    MYSQL_PWD="$password" mysqldump \
        --host="$(env_value DB_HOST)" --port="${DB_PORT_OVERRIDE:-$(env_value DB_PORT)}" --user="$user" \
        --single-transaction --routines --triggers --hex-blob \
        "$database" | gzip > "$file"
    echo "backup-db: $file"
}

dump "$(env_value DB_DATABASE)" "$(env_value DB_USERNAME)" "$(env_value DB_PASSWORD)"
USER_DB="$(env_value DB_DATABASE_USER)"
USER_NAME="$(env_value DB_USERNAME_USER)"
USER_PASSWORD="$(env_value DB_PASSWORD_USER)"
dump "${USER_DB:-roosko_user}" "${USER_NAME:-$(env_value DB_USERNAME)}" "${USER_PASSWORD:-$(env_value DB_PASSWORD)}"

find "$DEST" -name '*.sql.gz' -mtime +"$KEEP_DAYS" -delete
