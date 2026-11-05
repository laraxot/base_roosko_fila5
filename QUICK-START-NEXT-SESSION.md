# Quick Start Guide — Sessione +1

**Copy-paste this to start immediately**

---

## Step 1: Understand Your Options (2 min read)

```bash
cd /var/www/_bases/base_fixcity_fila5

# Read: which story to do?
cat docs/stories/READY-FOR-DEV-BATCH2.md | head -100
```

**Pick ONE**:
- **Story 1** (Media Security) — URGENT, 1-1.5h, highest priority
- **Story 2** (Lang Form) — CRITICAL, 1-1.5h, functionality blocker
- **Story 3** (User Migration) — HIGH, 1.5h, architecture debt
- **All 3** — If you have 3+ hours, do all

---

## Step 2: Copy-Paste Dev Checklist

**For Story 1 (Media Security)**:
```bash
cd laravel

# 1. Remove debug statements
grep -r "dddx" Modules/Media/app --include="*.php"
# ↑ Edit each file, remove dddx(...) calls

# 2. Fix MediaPolicy
# Edit: Modules/Media/app/Models/Policies/MediaPolicy.php
# Add to each method: return $user->id === $media->created_by || $user->isAdmin();

# 3. Fix VideoStream
# Edit: Modules/Media/app/Services/VideoStream.php
# In stream() method, add: if ($media->created_by !== auth()->id()) abort(403);

# 4. Verify
./vendor/bin/pest Modules/Media/tests
./vendor/bin/phpstan analyse Modules/Media

# 5. Re-audit
cd ..
npx claude-audit laravel/Modules/Media --output json,markdown
```

**For Story 2 (Lang Form)**:
```bash
cd laravel

# 1. Fix form schema
# Edit: Modules/Lang/app/Filament/Resources/TranslationFileResource.php
# Uncomment the form() method OR recreate it with fields

# 2. Fix model
# Edit: Modules/Lang/app/Models/Translation.php
# Choose: use $fillable XOR $guarded (not both)

# 3. Verify
./vendor/bin/pest Modules/Lang/tests
./vendor/bin/phpstan analyse Modules/Lang

# 4. Re-audit
cd ..
npx claude-audit laravel/Modules/Lang --output json,markdown
```

**For Story 3 (User Migration)**:
```bash
cd laravel

# See: docs/stories/USER-MIGRATION-REFACTOR-GUIDE.md (for pattern)

# Edit 3 migration files:
# - database/migrations/2024_01_01_000002_create_users_table.php
# - database/migrations/2024_01_01_000001_create_devices_table.php
# - database/migrations/2026_01_01_000035_create_model_has_roles_table.php

# Change:
# FROM: use Illuminate\Database\Migrations\Migration;
#       return new class extends Migration { ... }
# TO:   use Modules\Xot\Database\Migrations\XotBaseMigration;
#       return new class extends XotBaseMigration { ... }

# 4. Verify
php artisan migrate:rollback
php artisan migrate
./vendor/bin/pest Modules/User/tests
./vendor/bin/phpstan analyse Modules/User

# 5. Re-audit
cd ..
npx claude-audit laravel/Modules/User --output json,markdown
```

---

## Step 3: Mark as Done

**After each story completes**:
```bash
cd /var/www/_bases/base_fixcity_fila5

# Update master plan
cat >> docs/stories/MASTER-AUDIT-PLAN-20260709.md << 'EOF'

## Session +1 Update
- [x] Story 1: Media Security (DONE)
- [x] Story 2: Lang Form (DONE)
- ...

EOF

# Commit
git add -A
git commit -m "Batch 2 Phase A: Media security + Lang form fixes"
```

---

## Full Documentation (If you get stuck)

- **Strategy**: `docs/stories/MASTER-AUDIT-PLAN-20260709.md`
- **Stories**: `docs/stories/READY-FOR-DEV-BATCH2.md`
- **User Pattern**: `docs/stories/USER-MIGRATION-REFACTOR-GUIDE.md`
- **Index**: `docs/stories/INDEX-SESSION-20260709.md`

---

## Expected Outcomes

After this session:
- **Media**: 80/B → 85-88/A (security fixed ✅)
- **Lang**: 80/B → 85-88/A (form working ✅)
- **User**: 80/B → 85/A (architecture improved ✅)

---

## Time Budget

| Story | Dev | QA | Total |
|-------|-----|-----|-------|
| Media (Story 1) | 1-1.5h | 30 min | 1.5-2h |
| Lang (Story 2) | 1-1.5h | 30 min | 1.5-2h |
| User (Story 3) | 1.5h | 30 min | 2h |
| **All 3** | — | — | **5-5.5h** |

---

**Start with Story 1** (highest impact/priority ratio)

**Good luck!** 🚀
