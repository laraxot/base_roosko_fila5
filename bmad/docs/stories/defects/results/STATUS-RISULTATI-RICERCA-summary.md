Visual Parity Summary for /it/tests/risultati-ricerca

=== GAP ANALYSIS (from HTML comparison + BMAD) ===

1. **Card Structure** (structured + semantic)
   - Reference: `<div class="card shadow-sm px-4 pt-4 pb-4 rounded">` + `<span class="visually-hidden">Categoria:</span>` + `<a class="text-decoration-none title-xsmall-bold mb-2 category text-uppercase" href="#">Servizi</a>`
   - Local: `<div class="card">` (no shadow/padding/round) + `<span class="badge bg-primary">Servizio</span>` (badge vs link)
   - IMPACT: less accessibility, different visual styling, category is pill vs text link

2. **Results Header Bar** (workflow, UX)
   - Reference: `<div class="d-flex justify-content-between align-items-center border-bottom border-light pb-3 mb-2">` with count "645 Risultati" + mobile filter button + clear-filters button
   - Local: count only, no filter buttons
   - IMPACT: missing mobile filter + workflow for clearing filters

3. **Column Layout** (structure)
   - Reference: `.col-lg-8.offset-lg-1`
   - Local: `.col-lg-9`
   - IMPACT: slightly wider local results column, misalignment

4. **Rating Second Step** (feedback, thank-you)
   - Reference: `cmp-rating__card-second` thank-you card (`data-step="3"`) + positive feedback fieldset (`fieldset-rating-one`)
   - Local: rating works but missing thank-you and positive feedback
   - IMPACT: incomplete feedback UX

5. **Filter Sidebar** (content, search UX)
   - Reference: 7 tipologie + 50+ argomenti (checkbox list)
   - Local: 4 tipologie (no argomenti fieldset)
   - IMPACT: missing topical filters, less search granularity

6. **Modal Categories** (mobile UX)
   - Reference: `#modal-categories` modal with full filter list
   - Local: missing modal, filter button may not exist
   - IMPACT: mobile filter UX broken

=== CONCLUSION ===
Visual parity at 97% requires fixing the above gaps. All gaps are documented with BMAD stories (R02, R03, R04, R11, R12, R14, plus R01, R05-R09, R10, R13 for other pages). The 14 BMAD stories cover 10 identified defects + missing modal.

IMMEDIATE NEXT STEPS:
- Update `components/blocks/search/results.blade.php` with correct card structure, header bar, filter buttons, modal integration
- Update `components/blocks/rating/default.blade.php` with thank-you and positive feedback
- Expand filter JSON (add argomenti, make 7 categories)
- Add mobile modal component
- Verify after implementation with puppeteer screenshots (375/768/1440)

Evidence screenshots (reference vs local) are present in `/tmp/fixcity-parity/automated/risultati-ricerca-*.png`

CSS and button fixes already applied (search button now visible).