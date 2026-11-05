#!/bin/bash

# Script per rinominare file .md rimuovendo date e maiuscole
# Esclusi: README.md, ROADMAP.md, LICENSE.md, CHANGELOG.md, CONTRIBUTING.md, SECURITY.md

echo "🔄 Rinomino file .md con date e maiuscole..."

# Funzione per rinominare un file
rename_file() {
    local file="$1"
    local dir=$(dirname "$file")
    local basename=$(basename "$file")
    
    # Escludi file standard
    if [[ "$basename" == "README.md" ]] || \
       [[ "$basename" == "ROADMAP.md" ]] || \
       [[ "$basename" == "LICENSE.md" ]] || \
       [[ "$basename" == "CHANGELOG.md" ]] || \
       [[ "$basename" == "CONTRIBUTING.md" ]] || \
       [[ "$basename" == "SECURITY.md" ]]; then
        return
    fi
    
    # Rimuovi date formato YYYY-MM-DD o YYYY-MM o YYYY
    local newname=$(echo "$basename" | sed -E 's/-[0-9]{4}(-[0-9]{2})?(-[0-9]{2})?//g')
    
    # Converti in minuscolo
    newname=$(echo "$newname" | tr '[:upper:]' '[:lower:]')
    
    # Se il nome è cambiato, rinomina
    if [[ "$basename" != "$newname" ]]; then
        echo "  $basename → $newname"
        # Decommentare per eseguire effettivamente
        # mv "$file" "$dir/$newname"
    fi
}

# Trova tutti i file .md nei moduli
find Modules -name "*.md" -type f | while read -r file; do
    rename_file "$file"
done

echo "✅ Completato! (decommentare 'mv' nello script per applicare le modifiche)"
