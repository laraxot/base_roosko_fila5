---
id: comment-phpstan-analysis
title: PHPStan Analysis — Comment Module
phase: Analysis (BMAD)
status: verified
---

## 1. State (BMAD)
Module: `Modules/Comment` (Commenti, moderazione, notifiche, Filament 5).
Run: `vendor/bin/phpstan analyse Modules/Comment Modules/Blog`.

## 2. Errors Found (verified, 2026-10-15)
The scoped owner run now reports **0 errors** for Comment and Blog.

## 3. Purpose Verification
`InteractsWithComments` is **NOT dead code**. Cross-module usages:
- `Modules/Blog/app/Models/DemoCommentator.php` — consumer-side demo adapter using `InteractsWithComments`.
- `tests/Fixtures/Concerns/InteractsWithCommentsContractStub.php` — test stub implementing `CanComment`.
- `tests/Unit/Concerns/InteractsWithCommentsContractTest.php` — contract test.

The consumer-side contract is intentionally implemented outside the Comment core; Comment does not depend on a concrete
application user model.

## 4. Decision (BMAD Check)
The stale consumer references were replaced with the current domain models: Blog `Article` is the commentable fixture and
`DemoCommentator` is the demo-only adapter over the shared User table. No suppression comments, baselines, casts, or
`mixed` were added. The widget view is explicitly typed as `view-string`.

## 5. Verification
- `phpstan analyse Modules/Comment Modules/Blog`: 0 errors.
- PHP lint for all Comment and Blog PHP files: passed.
- Feature tests attempted but remain unverified because the local SQLite fixtures and Blog connection are not configured.
