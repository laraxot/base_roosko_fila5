---
<<<<<<< HEAD
<<<<<<< .merge_file_otcJWm
title: "PHPStan Level 10 Compliance — User Module"
=======
title: "PHPStan Level 10 Compliance: User Module"
>>>>>>> .merge_file_EtNtgn
=======
title: "PHPStan Level 10 Compliance: User Module"
>>>>>>> laraxot/dev
type: alias
status: superseded
canonical: "wiki/integrations/PHPSTAN_L10.md"
qmd: "phpstan_l10 alias superseded"
---

# PHPStan Level 10 Compliance: User Module

Documento identico al canonico: [PHPSTAN_L10.md](PHPSTAN_L10.md).

<<<<<<< HEAD
<<<<<<< .merge_file_otcJWm
<<<<<<< .merge_file_swThPG
| Aspect | Value |
|--------|-------|
| **PHPStan L10** | ✅ 0 errors |
| **Status** | Complete |
| **Last verified** | 2026-08-02 |

## Patterns Applied

### 1. User Model Types
```php
/** @return Collection<User> */
public function users(): Collection { }

/** @var class-string<User> */
protected $modelClass = User::class;
```

### 2. Authentication Guards
```php
/**
 * @param array<string, mixed> $credentials
 * @return User|null
 */
public function authenticate(array $credentials): ?User { }
```

### 3. Role & Permission Relationships
```php
/**
 * @return HasMany<Role>
 */
public function roles(): HasMany { }

/**
 * @return BelongsToMany<Permission>
 */
public function permissions(): BelongsToMany { }
```

## Verification

```bash
cd laravel/Modules/User
phpstan analyse app --level=10
# Expected: 0 errors found
```

## Related Docs

- [`phpstan-l10-compliance.md`](../../../../../../docs/wiki/rules/phpstan-l10-compliance.md)
- [`phpstan-l10-compliance.md`](../../../../../../docs/wiki/rules/phpstan-l10-compliance.md)
- [GitHub Repo](https://github.com/laraxot/module_user_fila5)

**Status:** ✅ Compliant (2026-08-02)
=======
Canonico: `laravel/Modules/User/docs/wiki/integrations/PHPSTAN_L10.md`. Qui non ci sono istruzioni: seguire il file collegato.
>>>>>>> .merge_file_hilgaO
=======
Canonico: `laravel/Modules/User/docs/wiki/integrations/PHPSTAN_L10.md`. Qui non ci sono istruzioni: seguire il file collegato.
>>>>>>> .merge_file_EtNtgn
=======
Canonico: `laravel/Modules/User/docs/wiki/integrations/PHPSTAN_L10.md`. Qui non ci sono istruzioni: seguire il file collegato.
>>>>>>> laraxot/dev
