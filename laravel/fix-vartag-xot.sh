#!/bin/bash

# Script per correggere errori varTag.differentVariable in Xot/app

echo "🔧 Correggo errori @var in foreach..."

# Trova tutti i file PHP in Xot/app
find Modules/Xot/app -name "*.php" -type f | while read -r file; do
    # Backup
    cp "$file" "$file.bak"
    
    # Pattern comuni da correggere:
    # @var Type $wrongName -> rimuovi o correggi
    
    # Per ora rimuovo i @var problematici nei foreach
    # PHPStan può inferire il tipo automaticamente
    
    # Rimuovi linee con solo /** @var ... */ prima di foreach
    perl -i -0pe 's/\n\s*\/\*\*\s*@var\s+[^\*]+\*\/\s*\n(\s*foreach)/\n$1/g' "$file"
    
    # Se il file è cambiato, mostra
    if ! cmp -s "$file" "$file.bak"; then
        echo "  ✓ $file"
    fi
    
    rm "$file.bak"
done

echo "✅ Completato!"
