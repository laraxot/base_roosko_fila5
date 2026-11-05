#!/bin/bash
# Automated PHPStan fix script - BMAD methodology

set -e

cd /var/www/_bases/base_fixcity_fila5/laravel

echo "=== PHPStan L10 Automated Fix Script ==="
echo "Timestamp: $(date)"

# Step 1: Run PHPStan and capture errors
echo "Step 1: Analyzing errors..."
./vendor/bin/phpstan analyse Modules --no-progress > /tmp/phpstan-errors.txt 2>&1 || true

# Count errors
ERROR_COUNT=$(grep -c "ERROR\|errors" /tmp/phpstan-errors.txt || echo "0")
echo "Errors found: $ERROR_COUNT"

# Step 2: Show first 20 errors for BMAD analysis
echo ""
echo "Step 2: Error categories:"
grep -E "method\.|return\.|parameter\.|class\." /tmp/phpstan-errors.txt | head -20 || echo "No categorizable errors"

# Step 3: Prepare for fixes
echo ""
echo "Step 3: Ready for BMAD fixes"
echo "Next: Apply category-specific fixes with BMAD reasoning"

