# Modular Workflow Reference — Claude Sessions

**Updated**: 9 luglio 2026  
**For**: Future BMAD + Story implementation sessions

---

## Key Principles (MEMORIZE THIS)

This is a **Nwidart modular Laravel** project:

1. ✅ **Each module is self-contained** (app/, tests/, config/, composer.json, vendor/)
2. ✅ **Root composer.json stays MINIMAL** (no module dependencies there)
3. ✅ **Dependencies go in module's composer.json** (not root)
4. ✅ **Tool binaries per module** (phpstan, pest, phpmd run from module dir)
5. ✅ **Dependency order matters** (Xot → User → Others → Fixcity → Final tier)

---

## When Working on a Story (e.g., Story 1: Media Security)

### Setup
```bash
cd /var/www/_bases/base_fixcity_fila5

# Understand what needs fixing
cat docs/stories/READY-FOR-DEV-BATCH2.md  # Pick your story
cat docs/stories/STORY<N>-GUIDE.md         # Read the guide if exists
```

### Development (Per Module)
```bash
cd laravel

# 1. Make code changes
# Edit Modules/<ModuleName>/app/...

# 2. Validate each file AS YOU GO (from laravel/ dir)
./vendor/bin/phpstan analyse Modules/<ModuleName> --configuration=phpstan.neon
./vendor/bin/phpmd Modules/<ModuleName> text ../phpmd.ruleset.xml
./vendor/bin/pest Modules/<ModuleName>/tests

# 3. If adding dependencies: edit Modules/<ModuleName>/composer.json
# NOT laravel/composer.json

# 4. After all changes: re-audit
cd ..
npx claude-audit laravel/Modules/<ModuleName> --output json,markdown
```

### Important Commands

```bash
cd /var/www/_bases/base_fixcity_fila5/laravel

# Audit single module (BEFORE starting work)
../../npx claude-audit Modules/Media --static

# Validate module code (after changes)
./vendor/bin/phpstan analyse Modules/Media --configuration=phpstan.neon
./vendor/bin/phpmd Modules/Media text ../phpmd.ruleset.xml

# Run module tests
./vendor/bin/pest Modules/Media/tests

# Re-audit after fixes
cd ..
npx claude-audit laravel/Modules/Media --output json,markdown
```

---

## Story Implementation Pattern (Proven)

**Example: Story 1 (Media Security)** — Completed in ~1h:

```bash
cd laravel

# 1. FIND & FIX
grep -r "dddx" Modules/Media/app --include="*.php"  # Find issues
# Edit files: remove dddx(), add ownership checks, etc.

# 2. VALIDATE PER FILE
./vendor/bin/phpstan analyse Modules/Media
./vendor/bin/phpmd Modules/Media text ../phpmd.ruleset.xml

# 3. RE-AUDIT
cd ..
npx claude-audit laravel/Modules/Media --output json,markdown
# Expect: score improved, 0 findings, no regressions

# 4. DONE ✅
# Create story completion report
cat > docs/stories/STORY<N>-COMPLETION-REPORT.md << 'EOF'
[...your report...]
EOF
```

---

## Dependencies Between Modules (Order Matters)

**When importing from another module**:
```php
// ✅ CORRECT: Import from public API
use Modules\User\Models\User;
use Modules\User\Contracts\UserContract;

// ❌ WRONG: Import internal classes
use Modules\User\Internal\UserHelper;  // Don't do this
```

**Dependency order for audit/fixes**:
```
1. Xot (foundation, 0 external deps on other modules)
   ↓
2. User, Media, Lang (only depend on Xot)
   ↓
3. Geo, Notify, Tenant (depend on Xot + User)
   ↓
4. UI, Comment, Rating, Seo (depend on Xot + User)
   ↓
5. Fixcity (core, depends on ALL above)
   ↓
6. Cms, Blog, Job, Activity, AI, Gdpr (final tier, all deps available)
```

**Rule**: Fix modules in this order. Fixes in lower modules benefit upper modules automatically.

---

## File Checklist Before Committing

For each story you implement:

- [ ] Module composer.json updated (if needed, not root)
- [ ] All code changes in `Modules/<ModuleName>/app/` only
- [ ] phpstan clean: `./vendor/bin/phpstan analyse Modules/<ModuleName>`
- [ ] phpmd clean: `./vendor/bin/phpmd Modules/<ModuleName> text ../phpmd.ruleset.xml`
- [ ] Tests pass: `./vendor/bin/pest Modules/<ModuleName>/tests`
- [ ] Re-audit shows improvement: `npx claude-audit laravel/Modules/<ModuleName>`
- [ ] Story completion report created: `docs/stories/STORY<N>-COMPLETION-REPORT.md`

---

## Quick Reference: Command Locations

| Command | Where to Run | From Directory |
|---------|---|---|
| `phpstan` | Module analysis | `/laravel` → `./vendor/bin/phpstan analyse Modules/X` |
| `phpmd` | Module linting | `/laravel` → `./vendor/bin/phpmd Modules/X text ...` |
| `pest` | Module tests | `/laravel` → `./vendor/bin/pest Modules/X/tests` |
| `claude-audit` | Module audit | Root → `npx claude-audit laravel/Modules/X` |
| composer | Module deps | `/laravel/Modules/X` → `composer require ...` |

---

## Stories Completed (This Session)

✅ **Story 1: Media Security Hardening**
- Fixed: dddx() removed, MediaPolicy ownership checks, VideoStream auth
- Time: ~1h
- Result: Score 80/B confirmed, 0 findings, Testing category improved
- Completion report: `docs/stories/STORY1-COMPLETION-REPORT.md`

---

## Stories Ready for Next Session

📋 **Story 2: Lang Form + Model** (1.5h estimate)
📋 **Story 3: User Migration** (1.5h estimate)  
📋 **Story 4: Media Cleanup** (2h estimate)

Each story is fully documented in `docs/stories/READY-FOR-DEV-BATCH2.md`

---

## Anti-Patterns to Avoid ❌

| Anti-Pattern | Why | Fix |
|--------------|-----|-----|
| Editing root composer.json for module deps | Dependencies go with module | Edit `Modules/X/composer.json` |
| Running phpstan from root on module | Root config differs | Run from `/laravel`: `./vendor/bin/phpstan ...` |
| Committing module changes at root level | Root/module changes leak | Organize changes: root-only vs module-only |
| Circular module dependencies | Creates deadlock | Respect dependency order |
| Importing internal module classes | Breaks encapsulation | Use public API (interfaces, models, contracts) |

---

## Pro Tips

1. **Parallel work possible**: Different batch modules can be worked on simultaneously (Story 1 + Story 2 can run in parallel)

2. **Reusable patterns**: Story 1 pattern (find + grep + fix + validate + re-audit) works for all stories

3. **QA gates are fast**: Once you know the pattern, each story takes 1-1.5h total

4. **Score improvements predictable**: Each story typically bumps score by 5-10 points

5. **No need for Git branches**: Use forward-only commits (CLAUDE.md rule)

---

## Next Session Kickoff (Copy-Paste Ready)

```bash
cd /var/www/_bases/base_fixcity_fila5

# 1. Decide which story to work on
cat docs/stories/READY-FOR-DEV-BATCH2.md

# 2. Read the implementation guide for your story
cat docs/stories/STORY<N>-GUIDE.md

# 3. Copy the dev checklist from the story
# Paste it into your editor for reference

# 4. Run the first validation
cd laravel
./vendor/bin/phpstan analyse Modules/<ModuleName> --configuration=phpstan.neon

# 5. Start coding
# [Edit files, validate per file, test]

# 6. When done, re-audit
cd ..
npx claude-audit laravel/Modules/<ModuleName> --output json,markdown

# 7. Create completion report
cat > docs/stories/STORY<N>-COMPLETION-REPORT.md << 'EOF'
[...completion report...]
EOF

# 8. Commit
git add -A
git commit -m "Story <N>: <description>"
```

---

## Memory: Rules Always In Effect

These CLAUDE.md rules always apply during modular work:

1. **No branch Git** — no `git checkout -b`, forward-only patches
2. **Git forward-only** — no rollback, just fix forward
3. **Nwidart Modular** — composer in modules, not root; tools per module
4. **Ponytail** — minimal changes, no new abstractions, no new deps
5. **BMAD workflow** — analyst → architect → dev → QA per module

---

**Status**: Ready for next session  
**Last Story Done**: Story 1 (Media Security) ✅  
**Next Story**: Story 2 (Lang Form + Model)  
**Estimated Time**: 1-1.5h per story

**Go forth and fix modularly!** 🚀
