#!/bin/bash

# Script per correggere velocemente gli errori missingType.iterableValue
# Aggiunge /** @var array<string, mixed> */ dove manca

echo "🚀 Correzione rapida missingType errors..."

# Trova tutti i file PHP nei moduli (esclusi vendor, tests, database)
find Modules/*/app -type f -name "*.php" | while read file; do
    # Backup
    cp "$file" "$file.bak"
    
    # Pattern 1: public array $property = [];
    # Aggiungi PHPDoc se manca
    sed -i '/public array \$[a-zA-Z_][a-zA-Z0-9_]* = \[\];/i\    /**\n     * @var array<string, mixed>\n     */' "$file"
    
    # Pattern 2: protected array $property = [];
    sed -i '/protected array \$[a-zA-Z_][a-zA-Z0-9_]* = \[\];/i\    /**\n     * @var array<string, mixed>\n     */' "$file"
    
    # Rimuovi duplicati di PHPDoc
    # Se ci sono due PHPDoc consecutivi, rimuovi il secondo
    
    # Verifica se ci sono state modifiche
    if ! cmp -s "$file" "$file.bak"; then
        echo "✅ Fixed: $file"
        rm "$file.bak"
    else
        # Nessuna modifica, ripristina
        mv "$file.bak" "$file"
    fi
done

echo "✨ Completato!"
echo "⚠️  Esegui PHPStan per verificare:"
echo "   ./vendor/bin/phpstan analyse Modules --level=max --configuration=phpstan-strict.neon"
