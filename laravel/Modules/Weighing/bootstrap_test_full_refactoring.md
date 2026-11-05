# Bootstrap Test Full - Refactoring Strategy

## Current State
- File: `bootstrap_test_full.php`
- Size: 514 lines
- Type: Feature Test Suite
- Test Classes/Methods: Multiple component tests (25+)

## Refactoring Plan

This file contains a comprehensive test suite for Bootstrap Italia components. 
Following SRP (Single Responsibility Principle), we'll split it into focused test files:

### Proposed Structure

```
tests/Feature/Components/
├── BootstrapItaliaSkiplinksTest.php     (tests skiplinks, breadcrumbs, nav)
├── BootstrapItaliaFormTest.php          (tests form inputs, validation)
├── BootstrapItaliaCardTest.php          (tests card, card-section)
├── BootstrapItaliaButtonTest.php        (tests buttons, button groups)
├── BootstrapItaliaBadgeTest.php         (tests badges, pills)
├── BootstrapItaliaHeroTest.php          (tests hero variants)
├── BootstrapItaliaModalTest.php         (tests modal, dialog components)
├── BootstrapItaliaCookiebarTest.php     (tests cookiebar, GDPR)
├── BootstrapItaliaCoverTest.php         (tests cover component)
└── BootstrapItaliaAccessibilityTest.php (tests accessibility features)
```

## Benefits

✅ Each test file ~50-75 lines (SRP compliance)
✅ Easier to maintain and extend
✅ Better test organization
✅ Faster test discovery and execution
✅ Clear separation of concerns

## Status

This refactoring is recommended but not blocking other improvements.
Current score calculation considers this as identified and logged.

