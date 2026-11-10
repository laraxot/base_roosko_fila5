<?php

declare(strict_types=1);

namespace Modules\Weighing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;

/**
 * Permessi usati dalle policy di Weighing (`{modello}.{abilita}`, guard `web`) e ruolo del pannello
 * `weighing::admin` (il nome coincide con l'id del pannello, `BaseUser::canAccessPanel`) con tutti i
 * permessi del modulo. Idempotente: si puo' rieseguire. Senza questi permessi `hasPermissionTo()` lancia
 * `PermissionDoesNotExist` e ogni utente non super-admin riceve un errore 500 (audit di sicurezza M4).
 */
class WeighingPermissionSeeder extends Seeder
{
    public const string GUARD = 'web';

    public const string ADMIN_ROLE = 'weighing::admin';

    /** Allineato alle chiamate `hasPermissionTo()` di app/Models/Policies (test WeighingAuthorizationTest). */
    public const array PERMISSIONS = [
        'scale.viewAny', 'scale.view', 'scale.create', 'scale.update', 'scale.delete', 'scale.readScale',
        'weighing.viewAny', 'weighing.view', 'weighing.create', 'weighing.update', 'weighing.void',
    ];

    public function run(): void
    {
        foreach (self::PERMISSIONS as $name) {
            Permission::findOrCreate($name, self::GUARD);
        }

        Role::findOrCreate(self::ADMIN_ROLE, self::GUARD)->givePermissionTo(self::PERMISSIONS);
    }
}
