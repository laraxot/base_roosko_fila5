---
title: "Event Module — BMAD Stories & Remaining Work"
type: concept
sources: ["../../AGENTS.md", "laravel/Modules/Event/docs/"]
confidence: medium
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, event, module, stories, remaining-work]
related:
  - ../../docs/second-brain/project/bmad-architecture.md
  - ../../docs/second-brain/project/bmad-stories.md
status: draft
qmd: "event module   bmad stories & remaining work event"
---

# Event Module — BMAD Stories & Remaining Work

## Module Overview
The Event module provides event management, calendar integration, and public event listing capabilities for the FixCity platform.

## Module Architecture
- **Core pattern**: QueueableAction, no controllers, no services
- **Key features**: Event creation, registration, calendar views, public indexing
- **Quality gates**: All must pass PHPStan level 10

## Remaining Work (BMAD Brainstorming)

### Critical / High Priority
1. **PHPStan Zero Verification** — Confirm all event contracts pass level 10
2. **Event Public Index** — Verify public event listing works correctly

### Medium Priority
3. **Module Documentation** — Document event models, actions, and calendar integration
4. **Test Coverage** — Add unit and integration tests for event processing
5. **Registration Flow** — Verify event registration and attendee management

### Low Priority / Polish
6. **Performance Optimization** — Review event listing and search performance
7. **Configuration Documentation** — Document event configuration options

## Verification Status
- PHPStan: ✅ Level 10, 0 errors (verified 2026-10-26)
- Module docs: 📋 Partial
- Test coverage: 📋 Needs verification
- BMAD stories: 📋 Needs module-specific story files

## Next Actions
1. Create module-specific story files in `laravel/Modules/Event/docs/stories/`
2. Verify all contracts pass PHPStan level 10
3. Add integration tests for event processing
4. Create gate files for any open stories