<?php

declare(strict_types=1);

namespace Modules\User\Models\Scopes;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
<<<<<<< HEAD
 * Scope che limita le query ai record associati al tenant corrente.
=======
 * Limita le query ai record del tenant Filament corrente.
 *
 * Fuori da un pannello con tenancy (pannelli di gestione del super admin, console,
 * code) `Filament::getTenant()` e' null e lo scope non filtra: la gestione vede tutto.
 *
 * La colonna e' qualificata con la tabella del modello: senza, una join con un'altra
 * tabella che ha `tenant_id` rende la condizione ambigua (errore SQL) o, peggio,
 * la applica alla tabella sbagliata.
>>>>>>> laraxot/dev
 *
 * @implements Scope<Model>
 */
class TenantScope implements Scope
{
<<<<<<< HEAD
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $_model): void
    {
        $tenant_id = Filament::getTenant()?->getKey();
        if ($tenant_id !== null) {
            $builder->where('tenant_id', '=', $tenant_id);
        }
=======
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = Filament::getTenant()?->getKey();
        if ($tenantId === null) {
            return;
        }

        $builder->where($model->qualifyColumn('tenant_id'), '=', $tenantId);
>>>>>>> laraxot/dev
    }
}
