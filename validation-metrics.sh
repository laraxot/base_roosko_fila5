#!/bin/bash

echo "======================================"
echo "FINAL VALIDATION METRICS COLLECTION"
echo "======================================"
echo ""

# 1. PHP Code Quality (PHPStan)
echo "📊 1. PHP CODE QUALITY (PHPStan Level 10)"
echo "==========================================="
php laravel/vendor/bin/phpstan analyze --configuration=laravel/phpstan.neon 2>&1 | tail -5
echo ""

# 2. File Structure Analysis
echo "📁 2. FILE STRUCTURE ANALYSIS"
echo "==============================="
TOTAL_FILES=$(find laravel -type f -name "*.php" | wc -l)
TOTAL_LINES=$(find laravel -type f -name "*.php" -exec wc -l {} + 2>/dev/null | tail -1 | awk '{print $1}')
echo "PHP Files: $TOTAL_FILES"
echo "Total PHP Lines: $TOTAL_LINES"
echo ""

# 3. Test Coverage
echo "🧪 3. TEST COVERAGE"
echo "===================="
TEST_FILES=$(find laravel -path "*tests*" -name "*.php" -type f | wc -l)
echo "Test Files: $TEST_FILES"
echo ""

# 4. Documentation
echo "📚 4. DOCUMENTATION"
echo "==================="
DOC_FILES=$(find docs -name "*.md" -type f | wc -l)
echo "Documentation Files: $DOC_FILES"
echo ""

# 5. Code Style
echo "🎨 5. CODE STYLE & FORMATTING"
echo "==============================="
php laravel/vendor/bin/pint --test laravel/app 2>&1 | grep -E "(✓|✗|checked)" | head -10 || echo "Pint format check (may need review)"
echo ""

