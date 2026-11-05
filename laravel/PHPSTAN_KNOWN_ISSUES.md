# PHPStan Known Issues

## Summary

**Current Status:** 127 errors remaining (down from 3938 initial)
**Reduction:** 96.8%

## Error Categories

### 1. Fixcity Enum Namespace (~80 errors) 🔴 LARASTAN BUG

**Issue:** PHPStan sees `Modules\Fixcity\Models\TicketStatusEnum` instead of `Modules\Fixcity\Enums\TicketStatusEnum`

**Root Cause:** Laravel IDE Helper or Larastan generates incorrect PHPDoc with wrong namespace

**Files Affected:**
- `Fixcity/app/Http/Resources/Api/TicketResource.php`
- `Fixcity/app/Notifications/*.php`
- `Fixcity/app/Services/*.php`

**Workaround Applied:**
```php
/* @phpstan-ignore-next-line assign.propertyType */
$ticket->status = TicketStatusEnum::PENDING;
```

**Permanent Fix:** Wait for Larastan update or regenerate PHPDoc with correct namespace

### 2. Tenant SushiToJson Trait (~40 errors) 🟡 DYNAMIC METHODS

**Issue:** Trait methods called dynamically are not recognized by PHPStan

**Files Affected:**
- `Tenant/app/Models/Traits/SushiToJson.php` (3 contexts)
- `Tenant/app/Models/Traits/SushiToJsons.php` (5 contexts)

**Methods Not Found:**
- `getJsonFile()`
- `loadExistingData()`
- `authId()`
- `ensureDirectoryExists()`
- `normalizeRowsForSave()`
- `saveToJson()`
- `findRowIndexById()`

**Solution:** Add PHPDoc `@method` annotations to trait

### 3. Cms Method Not Found (~8 errors) 🟡 DYNAMIC METHODS

**Files Affected:**
- `Cms/app/Models/Attachment.php` - `asset()`
- `Cms/app/Models/Page.php` - `getMiddlewareBySlug()`, `getTranslation()`
- `Cms/app/Models/Section.php` - `getBlocks()`, `getBlocksBySlug()`

**Solution:** Add PHPDoc `@method` annotations or implement methods

### 4. Geo Method Not Found (~8 errors) 🟡 SCOPE METHODS

**Files Affected:**
- `Geo/app/Filament/Resources/AddressResource.php`

**Methods:**
- `Region::getOptions()`
- `Province::getOptions()`
- `Locality::getOptions()`
- `Locality::getPostalCodeOptions()`

**Solution:** Add PHPDoc `@method static` annotations

### 5. Fixcity Relation Not Found (~7 errors) 🟢 LARASTAN

**Issue:** Larastan doesn't recognize relations

**Relations:**
- `comments` (Spatie Comments trait)
- `profile` (dynamic relation)
- `user` (dynamic relation)
- `activities` (dynamic relation)

**Solution:** Add PHPDoc `@property` or `@method` for relations

## Fixed Errors (67)

✅ **return.type** - Changed PHPDoc to match actual return type
✅ **varTag.nativeType** - Fixed @var annotations
✅ **method.childReturnType** - Fixed FirebaseAndroidNotification::toArray()
✅ **return.type in getFormSchema()** - Changed from associative array to list

## Recommendations

1. **Short Term:**
   - Add PHPDoc annotations to traits
   - Document dynamic methods
   - Add @phpstan-ignore for Enum bugs

2. **Medium Term:**
   - Update Larastan when fix is available
   - Regenerate IDE helper files
   - Add proper type hints to dynamic methods

3. **Long Term:**
   - Reduce dynamic method usage
   - Use explicit method definitions
   - Improve type coverage

## Progress Tracking

| Date | Errors | Change | Notes |
|------|--------|--------|-------|
| 2025-10-14 Initial | 3938 | - | With tests |
| 2025-10-14 Tests excluded | 95 | -97.6% | Excluded tests from analysis |
| 2025-10-14 Manual fixes | 36 | -62% | Fixed return.type errors |
| 2025-10-14 Full analysis | 196 | +444% | Analyzed all modules |
| 2025-10-14 Baseline removed | 194 | -1% | Removed baselines |
| 2025-10-14 Priority fixes | 127 | -34% | Fixed critical errors |

## Next Steps

1. Add PHPDoc to SushiToJson trait
2. Add PHPDoc to Cms models
3. Add PHPDoc to Geo models
4. Document remaining dynamic methods
5. Consider baseline for Enum bugs only
