---
type: story
slug: relative-public-symlinks
title: "Xot: symlink relativi per gli asset di moduli e temi, mai cancellare cartelle in public_html"
status: done
phase: DONE
owner: Xot
created: 2026-11-11
updated: 2026-11-11
---

# Xot: symlink relativi per gli asset di moduli e temi

## Regola

Gli asset non si copiano nella document root, si collegano con un symlink relativo:

```
public_html/themes/<Tema>     -> ../../laravel/Themes/<Tema>/public
public_html/assets/<modulo>   -> ../../laravel/Modules/<Modulo>/public
```

Il link relativo dipende solo dalla struttura del repository (`public_html/` accanto a `laravel/`).
Resta valido se il progetto cambia percorso: altro utente o home, container, cartella di release,
copia da sviluppo a produzione. Un link assoluto (`/home/utente/...`) invece si rompe. Per lo stesso
motivo `npm run copy` non serve più.

## Fatto

- `CreateRelativeSymlinkAction`: calcola la destinazione relativa dalla cartella del link e crea il link.
- `xot:theme-link`, `xot:module-link`, `symlink:publish` creano link relativi. Un link assoluto che punta
  alla stessa cartella viene riscritto relativo anche senza `--force`.
- `symlink:publish --force` non cancella più una cartella reale in `public_html`: la sposta in
  `<nome>.bak-<data>`, come già facevano gli altri due comandi. La document root può contenere file che
  esistono solo lì.

## Verifica

`tests/Unit/Actions/File/CreateRelativeSymlinkActionTest.php` verifica tre casi:

- il link creato è `../../laravel/Themes/TwentyOne/public`;
- il link funziona anche dopo aver spostato il progetto;
- un link assoluto non risulta relativo.

PHPStan 0 sui file toccati.
