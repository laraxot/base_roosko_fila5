# Base Roosko Fila5 — PROJECT.md

## Context

Laravel 13 + Filament 5 modular monolith (Laraxot architecture) for waste treatment and sorting plants. Each module is its own repository and has its own admin panel at `/{module}/admin`; the tenant is `config/local/roosko` (main module: Roosko).

Domain modules: Roosko (product), Warehouse, Processing, Weighing, Labeling (generic and reusable), Waste and Rentri (regulatory). See [README.md](README.md).

## Current Milestone

**M1: first runnable installation**

- Tenant `config/local/roosko` and `config/localhost` with the Roosko module set ✅
- Domain modules synced from their repositories ✅
- Install vendor and run PHPStan level 10 and Pest on the seven domain modules — pending
- Verify the RENTRI integration on the demo environment (`https://demoapi.rentri.gov.it`) with a demo credential — pending
- User manual screenshots from the running application — pending

## Open Items

- Digital FIR signature with PIN has no button in the panels yet.
- Unit labels do not print the EWC code and hazard classes yet.
- Outgoing shipments do not orchestrate an outbound weighing yet.
- Processing's own "Registra output" button bypasses Roosko lots: hide it in the Roosko installation.

## Rules

- Business logic only in Spatie queueable actions; no Services, no Jobs.
- DTOs with spatie/laravel-data; states as backed enums with `canTransitionTo()`.
- No `->label()`: every label comes from `lang/{it,en}`.
- Cross-module foreign keys are indexed columns without constraints; each module has its own connection.
- PHPStan level 10, Pest, DRY and KISS.
