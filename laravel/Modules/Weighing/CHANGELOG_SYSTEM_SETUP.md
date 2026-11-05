# 🚀 Changelog & Release System — Setup Summary

Sistema completo di **semantic versioning**, **changelog generation**, e **contributor stats** implementato il 2026-06-17.

## ✅ Implementato

### 📁 Struttura Creata

```
✅ docs/changelogs/
   ├── README.md                      # Documentazione sistema
   ├── CHANGELOG.md                   # Changelog principale
   ├── 2026-W25.md                    # Esempio changelog settimanale
   └── 2026-W26.md                    # Altro changelog settimanale

✅ bashscripts/release/
   ├── README.md                      # Guida di riferimento
   ├── changelog-advanced.js          # Plugin semantic-release custom
   ├── setup-module-releases.sh       # Script di configurazione moduli
   └── .releaserc.module.template.json # Template per .releaserc

✅ .releaserc.json (root - aggiornato)
   └── Configurato per changelog avanzato + settimanale

✅ laravel/Modules/Xot/.releaserc.json (esempio)
   └── Template configurato per moduli

✅ .github/workflows/
   ├── semantic-release-advanced.yml      # Workflow per root (nuovo)
   ├── semantic-release-module.yml        # Workflow per moduli (nuovo)
   ├── semantic-release-monorepo.yml      # Workflow master (aggiornato)
   └── reusable-semantic-release.yml      # Workflow reusable (aggiornato)
```

### 🔧 Configurazione

#### 1. Root Package

- `.releaserc.json` configurato per:
  - Conventional commits analyzer
  - Release notes generator
  - Changelog in `docs/changelogs/CHANGELOG.md`
  - **Advanced changelog** con stats settimanali (nuovo!)
  - GitHub releases
  - Auto-commit e push

#### 2. GitHub Actions

**4 Workflow configurati**:

| Workflow | Trigger | Scopo |
|----------|---------|-------|
| `semantic-release-monorepo.yml` | Push main/dev | Orchestra root + moduli + temi |
| `reusable-semantic-release.yml` | Called by monorepo | Esegue release singolo package |
| `semantic-release-advanced.yml` | Push main/dev | Alternative con advanced changelog |
| `semantic-release-module.yml` | Workflow call | Release singolo modulo |

#### 3. Custom Plugin

**`changelog-advanced.js`** — Plugin semantic-release che genera:

```javascript
// Input: git log, diffs, contributors
// Output: File settimanale con:
// - Numero commit/contributors
// - Files changed per tipo (PHP, Blade, JS, etc.)
// - Top 10 contributors
// - Stats di addizioni/deletions
```

#### 4. Setup Script

**`setup-module-releases.sh`** — Configura automaticamente:

```bash
./bashscripts/release/setup-module-releases.sh          # Preview
./bashscripts/release/setup-module-releases.sh execute  # Esegui
```

Crea per ogni modulo/tema:
- `.releaserc.json`
- `CHANGELOG.md` template

### 📊 Output Atteso

Dopo il primo push su `main`/`dev`:

```
docs/changelogs/
├── CHANGELOG.md
│   [1.5.0] - 2026-06-18
│   - Features
│   - Bug Fixes
│   - Contributors
│
├── 2026-W25.md
│   - Version, Date
│   - Commits: 42, Contributors: 8, Files: 156
│   - Files by Type (files + lines):
│     - PHP: 34 files, 2,847 lines
│     - Blade: 18 files, 1,234 lines
│     - JavaScript: 15 files, 567 lines
│   - Top contributors (da git)
│   - Release notes
│
└── 2026-W26.md
    (prossima settimana...)
```

Ogni modulo ha il suo:
- `laravel/Modules/User/CHANGELOG.md`
- `laravel/Modules/Geo/CHANGELOG.md`
- etc.

## 🚦 Prossimi Step

### 1. Configura tutti i moduli (opzionale ma consigliato)

```bash
cd /var/www/_bases/base_fixcity_fila5
./bashscripts/release/setup-module-releases.sh execute
```

### 2. Committa le configurazioni

```bash
git add -A
git commit -m "chore(release): setup semantic-release system with advanced changelog"
git push origin dev
```

### 3. Verifica il workflow

- Vai a GitHub → **Actions**
- Vedi `semantic-release-monorepo.yml` in esecuzione
- Aspetta completamento (5-10 min)
- Controlla:
  - GitHub **Releases** (nuovo tag)
  - `docs/changelogs/CHANGELOG.md` (aggiornato)
  - `docs/changelogs/2026-W25.md` (nuovo file settimanale)

### 4. Usa conventional commits nei commit futuri

```bash
# Questo triggerà patch version
git commit -m "fix(geo): correct coordinate calculation"

# Questo triggerà minor version
git commit -m "feat(user): add 2FA authentication"

# Questo triggerà major version
git commit -m "feat(api)!: change endpoint structure"
```

## 📚 Documentazione

- **Sistema completo**: [`docs/changelogs/README.md`](docs/changelogs/README.md)
- **Guida uso**: [`bashscripts/release/README.md`](bashscripts/release/README.md)
- **Conventional Commits**: https://www.conventionalcommits.org/
- **Semantic Release**: https://semantic-release.gitbook.io/

## 🎯 Benefici

✅ **Automatico** — Release e changelog senza intervento manuale  
✅ **Trasparente** — Versioning basato su commit message  
✅ **Modulare** — Ogni package versionato indipendentemente  
✅ **Tracciabile** — Weekly stats con contributor info  
✅ **Monorepo-ready** — Supporta root + 20+ moduli + temi  
✅ **Customizzabile** — Template e script modificabili  

## 📋 Checklist

- [x] Crea struttura `docs/changelogs/`
- [x] Implementa `changelog-advanced.js` plugin
- [x] Crea GitHub Actions workflows
- [x] Aggiorna `.releaserc.json` root
- [x] Crea template moduli
- [x] Crea setup script
- [x] Scrivi documentazione
- [x] Rendi script eseguibili
- [ ] Esegui `setup-module-releases.sh execute`
- [ ] Committa configurazioni
- [ ] Verifica workflow su GitHub

## 🔗 File Chiave

```
.
├── .releaserc.json                    ← Root config (aggiornato)
├── .github/workflows/
│   ├── semantic-release-monorepo.yml  ← Main workflow (aggiornato)
│   ├── reusable-semantic-release.yml  ← Base workflow (aggiornato)
│   ├── semantic-release-advanced.yml  ← Alternativa (nuovo)
│   └── semantic-release-module.yml    ← Per moduli (nuovo)
├── bashscripts/release/
│   ├── README.md                      ← Guida (nuovo)
│   ├── changelog-advanced.js          ← Plugin custom (nuovo)
│   ├── setup-module-releases.sh       ← Setup script (nuovo)
│   └── .releaserc.module.template.json ← Template (nuovo)
├── docs/changelogs/
│   ├── README.md                      ← Docs sistema (nuovo)
│   ├── CHANGELOG.md                   ← Changelog root (nuovo)
│   └── weekly/
│       └── 2026-W25.md                ← Esempio weekly (nuovo)
└── laravel/Modules/Xot/
    └── .releaserc.json                ← Esempio module (aggiornato)
```

---

**Setup completato** | 2026-06-17 | By Claude Code  
**Pronto per**: Semantic Release + Advanced Changelog + Weekly Stats

## 💡 Pro Tips

1. **Leggi il README**: Prima di usare il sistema, leggi `bashscripts/release/README.md`
2. **Test dry-run**: Usa `setup-module-releases.sh` senza `execute` per preview
3. **Monitora Actions**: Guarda i log di GitHub Actions se qualcosa fallisce
4. **Tag manuali**: Se hai già tag, semantic-release li rispetterà
5. **Monorepo**: Usa `feat(scope):` nei commit per indicare il package modificato

## 🆘 Support

Se qualcosa non funziona:

1. Controlla `bashscripts/release/README.md` → Troubleshooting
2. Verifica GitHub Actions logs
3. Leggi https://semantic-release.gitbook.io/
4. Controlla commit message format (conventional commits)
