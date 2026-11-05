# Code Quality Improvement - BMAD Strategy

**Methodology**: BMAD (Baseline → Microanalysis → Action → Delivery)  
**Tools**: PHPStan L10, Pest Coverage, Filament Blueprint, Second Brain  
**Timeline**: Autonomous execution, no confirmation needed

---

## BASELINE (B) - Current State Assessment

### PHPStan L10 Status
- Target: 0 errors ✓ (in progress - 3 severe errors to fix)
- Strategy: Remove stale references, fix bootstrap errors
- Last run: [Pending completion]

### Test Coverage Status  
- Target: ≥80% per module
- Current: ~5% (most modules have 1 test file only)
- Gap: Massive - need comprehensive Pest test suite

### Code Metrics
- Modules: 19 (all need quality improvements)
- Key issues:
  - Incomplete test coverage
  - Missing Filament resource validations
  - No type safety in stubs
  - Missing documentation

---

## MICROANALYSIS (M) - Problem Categorization

### Category 1: Static Analysis (PHPStan)
**Problem**: 3 severe errors blocking analysis
- XotCovPivot.php reference error
- ColumnsBehaviorTest.php reference error  
- Bootstrap file read error
**Solution**: Remove stale references, validate all scanFiles exist
**Priority**: CRITICAL (blocks full analysis)

### Category 2: Test Coverage
**Problem**: Most modules have <10% coverage
- AI: 1 test file
- Activity: 1 test file
- Blog, Cms, Comment, Fixcity, etc: all 1 test file each
**Solution**: Write Pest test suites targeting 80% coverage per module
**Priority**: HIGH (blocks deployment readiness)

### Category 3: Type Safety
**Problem**: Stub classes missing method implementations
- Return type mismatches
- Missing parameters in overrides
- No validation in Filament resources
**Solution**: Complete all stub implementations with proper types
**Priority**: HIGH (affects PHPStan cleanliness)

### Category 4: Filament Blueprint Compliance
**Problem**: Resources/Pages may not follow best practices
- Missing type hints on query builders
- No proper policy authorization
- Missing table validations
**Solution**: Apply Filament Blueprint architecture to all resources
**Priority**: MEDIUM (code quality, not blocking)

### Category 5: Documentation
**Problem**: Missing architecture docs, BMAD stories
**Solution**: Document every fix with BMAD story format
**Priority**: MEDIUM (enables future maintenance)

---

## ACTION (A) - Execution Plan

### Phase 1: Fix PHPStan (CRITICAL)
```bash
# 1. Remove stale references from phpstan.neon
sed -i '/XotCovPivot/d; /ColumnsBehaviorTest/d' phpstan.neon

# 2. Verify bootstrap
./vendor/bin/phpstan analyse Modules --no-progress

# 3. Fix any remaining severe errors
# Expected: 0 errors or only regular type/method errors
```

### Phase 2: Test Coverage (HIGH)
For each module (parallel execution):
1. Analyze current code structure
2. Identify 20 most critical functions to test
3. Write Pest tests targeting 80% coverage
4. Run `php artisan test --coverage` to verify
5. Document in BMAD story

### Phase 3: Type Safety (HIGH)
1. Review all stub class implementations
2. Ensure method signatures match parent classes
3. Add proper return types and parameters
4. Verify PHPStan passes for each stub
5. Document pattern in second brain

### Phase 4: Filament Blueprint (MEDIUM)
1. Audit all Filament resources for:
   - Proper type hints on getTableQuery()
   - Authorization policies
   - Form validations
   - Table column definitions
2. Apply Filament Blueprint patterns
3. Document in module docs

### Phase 5: Documentation (MEDIUM)
1. Create BMAD story for each significant fix
2. Update module README files
3. Add architecture decisions to docs
4. Record lessons in second brain

---

## DELIVERY (D) - Verification & Tracking

### Quality Gates
- [ ] PHPStan: 0 errors
- [ ] Pest Coverage: ≥80% per module
- [ ] All stubs have valid signatures
- [ ] All Filament resources follow Blueprint
- [ ] All fixes documented in BMAD stories
- [ ] Memory updated with solutions

### Automation
```bash
# Continuous quality checks
./vendor/bin/phpstan analyse Modules
php artisan test --coverage --min=80
php artisan pint --check
```

### Success Criteria
✓ All 19 modules: 0 PHPStan errors each
✓ All 19 modules: ≥80% test coverage each
✓ All Filament resources: Blueprint-compliant
✓ Complete documentation trail (BMAD stories)
✓ Memory system updated (reusable patterns)

---

## SECOND BRAIN INTEGRATION

### Memory Records to Create/Update
1. `phpstan-zero-errors-checklist.md` - Steps to maintain clean analysis
2. `pest-coverage-80-percent-strategy.md` - Test writing patterns for high coverage
3. `filament-blueprint-patterns.md` - Resource/page best practices
4. `bmad-story-template.md` - Reusable story format
5. `code-quality-gate-automation.md` - CI/CD integration

### Filament Blueprint Resources
Document for each resource:
- Query builder type hints
- Policy authorization flow
- Table column definitions
- Form field validations
- Action handlers

---

## ESTIMATED TIMELINE
- Phase 1 (PHPStan): 15 mins
- Phase 2 (Test Coverage): 4-6 hours (parallel for all modules)
- Phase 3 (Type Safety): 2 hours
- Phase 4 (Filament Blueprint): 3 hours
- Phase 5 (Documentation): 2 hours

**Total**: ~10-12 hours autonomous work

---

## NEXT IMMEDIATE ACTIONS
1. ✓ Get PHPStan final output (in progress)
2. Fix remaining PHPStan errors
3. Begin Pest coverage audit per module
4. Start writing test suites in parallel
5. Document all fixes in BMAD story format
