# S351: Risolvere 14 Errori PHPStan in Laravel Modules

**Data creazione**: 2026-06-12  
**Sprint**: 1  
**Priority**: Must  
**Status**: ready-for-dev  
**Points**: 8  

---

## 📋 Riepilogo

Risolvere 14 errori di type-safety PHPStan identificati nella directory `Modules` senza modificare `phpstan.neon`, migliorare la documentazione nel secondo cervello, e stabilire una corretta gerarchia TestCase seguendo le linee guida di laravel-modules.

---

## GitHub (tracciamento)

| Risorsa | URL |
|---------|-----|
| Issue Principale | https://github.com/marco76tv/fixcity/issues/351 |
| Discussione | https://github.com/marco76tv/fixcity/discussions/351 |

---

## 🎯 Acceptance Criteria

### Criteri di Completamento Tecnici

```gherkin
Feature: PHPStan Type Safety Compliance
  Scenario: Risolvere tutti i 14 errori PHPStan
    Given eseguo './vendor/bin/phpstan analyse Modules' dalla cartella Laravel
    When analizzo i 14 errori identificati
    Then correggo ogni errore senza modificare phpstan.neon
    And PHPStan restituisce "0 errors"

  Scenario: Type annotations corrette
    Given leggo la gerarchia TestCase di laravel-modules
    When studio https://github.com/nWidart/laravel-modules
    Then le TestCase dei Modules estendono XotBaseTestCase
    And XotBaseTestCase estende Nwidart\Modules\Tests\BaseTestCase
```

### Criteri di Accettazione Qualità

- ✅ PHPStan clean: 0 errori in `./vendor/bin/phpstan analyse Modules`
- ✅ Documentazione aggiornata in `docs/wiki/` per il secondo cervello
- ✅ Gerarchia TestCase standardizzata e documentata
- ✅ Commit con message chiaro e tracciabile

---

## 🔍 Errori PHPStan Identificati

### Riepilogo per Modulo

| Modulo | File | Linea | Errore | Tipo |
|--------|------|-------|--------|------|
| Cms | HeaderNavJsonTest.php | 27 | return.type | array vs array<string, mixed> |
| Cms | HeaderNavJsonTest.php | 44 | return.type | mixed vs list<array<string, mixed>> |
| Cms | IndividualFolioRoutesTest.php | 323 | argument.type | array<mixed> expected |
| Cms | IndividualFolioRoutesTest.php | 326 | offsetAccess.nonOffsetAccessible | Cannot access mixed |
| Cms | IndividualFolioRoutesTest.php | 327 | foreach.nonIterable | mixed not iterable |
| Cms | IndividualFolioRoutesTest.php | 328 | offsetAccess.nonOffsetAccessible | Cannot access 'data' on mixed |
| Cms | RegisterComponentTest.php | 56 | method.notFound | ReflectionType::getName() undefined |
| Cms | PageSchemaBuilderTest.php | 25 | return.type | mixed vs array<string, mixed> |
| Xot | CopyFromLastYearAction.php | 34 | argument.type | array vs array<string, mixed> |
| Xot | CopyFromLastYearAction.php | 34 | argument.type | array vs array<string, mixed> (param #2) |
| Xot | GetModuleConfigAction.php | 31 | return.type | array<mixed, mixed> vs array<string, mixed> |
| Xot | Helper.php | 442 | missingType.parameter | $user parameter type missing |
| Xot | HasTableWithXotTestClass.php | 36 | return.type | Mockery\MockInterface vs Filament\Tables\Table |
| Xot | HasTableWithoutOptionalMethodsTestClass.php | 35 | return.type | Mockery\MockInterface vs Filament\Tables\Table |

---

## 📂 File da Modificare

### Cms Module

1. **Cms/tests/Feature/HeaderNavJsonTest.php** (linee 27, 44)
   - Aggiungere type hints ai metodi
   - Pattern: `array<string, mixed>`, `list<array<string, mixed>>`

2. **Cms/tests/Feature/IndividualFolioRoutesTest.php** (linee 323-328)
   - Aggiungere type assertion prima dell'accesso
   - Pattern: `/** @var array<string, mixed> $response */'`

3. **Cms/tests/Unit/Http/Volt/RegisterComponentTest.php** (linea 56)
   - Sostituire `ReflectionType::getName()` con metodo disponibile
   - Usare `$type->getName()` se esiste, altrimenti `(string) $type`

4. **Cms/tests/Unit/Support/PageSchemaBuilderTest.php** (linea 25)
   - Aggiungere return type hint: `array<string, mixed>`

### Xot Module

5. **Xot/app/Actions/Filament/Actions/CopyFromLastYearAction.php** (linea 34)
   - Aggiungere type hints ai parametri: `array<string, mixed>`

6. **Xot/app/Actions/Module/GetModuleConfigAction.php** (linea 31)
   - Cambiare return type da `array<mixed, mixed>` a `array<string, mixed>`

7. **Xot/helpers/Helper.php** (linea 442)
   - Aggiungere type hint al parametro `$user`
   - Usare interface/type appropriato (es: `?User`, `?Authenticatable`)

8. **Xot/tests/Unit/Support/HasTableWithXotTestClass.php** (linea 36)
   - Return type mock come `Filament\Tables\Table`
   - Pattern: Usare `@return \Filament\Tables\Table&\Mockery\MockInterface`

9. **Xot/tests/Unit/Support/HasTableWithoutOptionalMethodsTestClass.php** (linea 35)
   - Stesso pattern di HasTableWithXotTestClass.php

---

## 🏗️ Architettura e Pattern

### TestCase Hierarchy (da documentare)

```php
// Gerarchia corretta secondo laravel-modules
Nwidart\Modules\Tests\BaseTestCase
  ↓
Xot\Tests\Unit\XotBaseTestCase (o simile)
  ↓
Modules\<ModuleName>\Tests\TestCase
  ↓
Modules\<ModuleName>\Tests\Feature\<YourFeatureTest>
Modules\<ModuleName>\Tests\Unit\<YourUnitTest>
```

### PHPStan Type Safety Rules

1. **Tutti i parametri devono avere type hints**
   - Exception: `mixed` se realmente necessario con commento
   - Preferire union types piuttosto che `mixed`

2. **Tutti i metodi di ritorno devono avere type hints**
   - `array` → specificare keys e values: `array<string, mixed>`
   - `mixed` → usare type specifico o union type

3. **Test methods con mock objects**
   - Pattern per Filament Table mock:
     ```php
     /** @var \Filament\Tables\Table&\Mockery\MockInterface $mock */
     $mock = \Mockery::mock(\Filament\Tables\Table::class);
     ```

4. **Config::get() restituisce mixed**
   - Pattern: `$config = Config::get('key', []); $config = is_array($config) ? $config : [];`
   - Oppure type assertion: `/** @var array<string, mixed> $config */`

---

## 🔧 Implementazione Passo per Passo

### Step 1: Preparazione e Analisi (30 min)

```bash
# 1. Esegui PHPStan per ottenere elenco errori completo
cd /var/www/_bases/base_fixcity_fila5/laravel
./vendor/bin/phpstan analyse Modules --no-progress > phpstan-errors.txt

# 2. Salva output per riferimento
# 3. Leggi ogni file sorgente per comprendere il contesto
```

### Step 2: Correzioni Cms Module (60 min)

Leggi e correggi ogni file secondo i pattern sopra descritti:

1. `Modules/Cms/tests/Feature/HeaderNavJsonTest.php`
2. `Modules/Cms/tests/Feature/IndividualFolioRoutesTest.php`
3. `Modules/Cms/tests/Unit/Http/Volt/RegisterComponentTest.php`
4. `Modules/Cms/tests/Unit/Support/PageSchemaBuilderTest.php`

### Step 3: Correzioni Xot Module (60 min)

1. `Modules/Xot/app/Actions/Filament/Actions/CopyFromLastYearAction.php`
2. `Modules/Xot/app/Actions/Module/GetModuleConfigAction.php`
3. `Modules/Xot/helpers/Helper.php`
4. `Modules/Xot/tests/Unit/Support/HasTableWithXotTestClass.php`
5. `Modules/Xot/tests/Unit/Support/HasTableWithoutOptionalMethodsTestClass.php`

### Step 4: Validazione PHPStan (30 min)

```bash
cd /var/www/_bases/base_fixcity_fila5/laravel
./vendor/bin/phpstan analyse Modules --no-progress
# Risultato atteso: [ERROR] Found 0 errors
```

### Step 5: Documentazione e Secondo Cervello (30 min)

1. Crea/aggiorna: `docs/wiki/phpstan/modules-type-safety-patterns.md`
2. Crea/aggiorna: `docs/wiki/laravel-modules/testcase-hierarchy.md`
3. Aggiorna indici in `docs/wiki/INDEX.md`
4. Documenta pattern pattern in `docs/chat/` per future reference

---

## 📚 Documentazione da Migliorare

### Nuovi File da Creare

1. **docs/wiki/phpstan/modules-type-safety-patterns.md**
   - Pattern tipo safety per array
   - Pattern per Config::get()
   - Pattern per Mockery mocks
   - Pattern per metodi ricorsivi

2. **docs/wiki/laravel-modules/testcase-hierarchy.md**
   - Gerarchia TestCase corretta
   - Come estendere BaseTestCase
   - Best practices da laravel-modules docs

3. **docs/chat/phpstan-modules-errors.md**
   - Chat record di questo lavoro
   - Errori riscontrati e soluzioni
   - Pattern scoperti e documentati

### File da Aggiornare

1. **docs/wiki/INDEX.md** - Aggiungere link ai nuovi documenti
2. **docs/wiki/phpstan/INDEX.md** - Se esiste, aggiungere patterns
3. **docs/wiki/laravel-modules/INDEX.md** - Se esiste, aggiungere testcase-hierarchy

---

## 🎓 Apprendimento (Secondo Cervello)

### Cosa Imparare

1. **PHPStan type system**
   - `array<string, mixed>` vs `array`
   - `list<T>` vs `array<int, T>`
   - Union types e intersection types
   - Generic types in PHP 7.4+

2. **laravel-modules architecture**
   - Gerarchia BaseTestCase
   - Come strutturare test in moduli
   - Configurazione PHPUnit per moduli

3. **Mockery + Filament patterns**
   - Come mockare Filament components
   - Return types per mocked objects
   - Generic mock typing

### Documenti di Riferimento

- [laravel-modules docs](https://laravelmodules.com/docs/13/advanced/tests)
- [github.com/nWidart/laravel-modules](https://github.com/nWidart/laravel-modules)
- [PHPStan documentation](https://phpstan.org/)

---

## 🚀 Testing Strategy

### Pre-Correzioni
```bash
./vendor/bin/phpstan analyse Modules --no-progress
# Risultato: [ERROR] Found 14 errors
```

### Post-Correzioni
```bash
./vendor/bin/phpstan analyse Modules --no-progress
# Risultato atteso: [ERROR] Found 0 errors
```

### Validazione Aggiuntiva
```bash
# Esegui test suite per assicurare che nulla sia rotto
cd /var/www/_bases/base_fixcity_fila5/laravel
./vendor/bin/pest Modules/ --configuration phpunit.xml
```

---

## 📝 Developer Notes

### Vincoli Importanti

1. **NON modificare phpstan.neon** - Solo l'utente può
2. **NON ignorare errori con @phpstan-ignore** - Risolvili
3. **NON usare assert() o cast** per evitare errori - Usa type hints veri
4. **NON duplicare codice** - Usa pattern standard

### Strumenti Utili

- QMD: Per cercare pattern esistenti in codebase
- Second brain (LLM Wiki): Per documentare scoperte
- PHPStan help: `./vendor/bin/phpstan --help`

### Soluzioni Rapide

Se trovi problemi durante l'implementazione:
1. Cerca il pattern in codebase via QMD
2. Leggi l'errore PHPStan con URL documentation
3. Documenta la soluzione nel wiki per futura referenza
4. Usa tag di commit `/docs` per documentazione-only commits

---

## ✅ Completion Checklist

- [ ] Leggo completamente la story e capisco scope
- [ ] Analizzo i 14 errori PHPStan in dettaglio
- [ ] Correggo errori in Cms module (4 file)
- [ ] Correggo errori in Xot module (5 file)
- [ ] Eseguo validazione PHPStan (0 errori)
- [ ] Eseguo test suite (tutti passano)
- [ ] Aggiorno wiki con type safety patterns
- [ ] Aggiorno wiki con laravel-modules testcase hierarchy
- [ ] Aggiorno indici nei wiki
- [ ] Documento chat di questa sessione in `/docs/chat/`
- [ ] Creo commit con messaggio chiaro
- [ ] Aggiorno sprint-status.yaml se necessario

---

## 🔗 Correlazioni

- **STORY-016**: Ticket model PHPStan + enum cleanup (relazionato)
- **STORY-045**: PHPStan 0 errori Fixcity module (dopo questo)
- **Memoria**: docs/wiki/phpstan/modules-type-safety-patterns.md

---

**Status**: Ready for Dev  
**Assegnato a**: Developer Agent  
**Data inizio**: 2026-06-12  
**Data target completamento**: 2026-06-12 (stesso giorno)
