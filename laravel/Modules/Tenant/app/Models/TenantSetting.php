<?php

declare(strict_types=1);

namespace Modules\Tenant\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Tenant\Models\Traits\TenantSettingRelations;

/**
 * @property string|null $tenant_id
 * @property string|null $key
 * @property string|null $value
 * @property string|null $type
 * @property-read Tenant|null $tenant
 *
 * @method static Builder<static>|TenantSetting newModelQuery()
 * @method static Builder<static>|TenantSetting newQuery()
 * @method static Builder<static>|TenantSetting query()
 *
 */
class TenantSetting extends BaseModel
{
    use TenantSettingRelations;

    protected $fillable = [
        'tenant_id',
        'key',
        'value',
        'type',
    ];
}
