<?php

declare(strict_types=1);

namespace Modules\Weighing\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Weighing\Database\Factories\ScaleFactory;
use Modules\Weighing\Enums\ScaleDriverEnum;
use Modules\Weighing\Enums\ScaleTypeEnum;

/**
 * Strumento di pesatura.
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property ScaleTypeEnum $type
 * @property ScaleDriverEnum $driver
 * @property string|null $host
 * @property int|null $port
 * @property string $max_capacity_kg
 * @property string $resolution_kg
 * @property CarbonImmutable|null $verified_until
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property-read Collection<int, Weighing> $weighings
 *
 * @method static Builder<static>|Scale newModelQuery()
 * @method static Builder<static>|Scale newQuery()
 * @method static Builder<static>|Scale query()
 * @method static ScaleFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class Scale extends BaseModel
{
    use SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'code',
        'name',
        'type',
        'driver',
        'host',
        'port',
        'max_capacity_kg',
        'resolution_kg',
        'verified_until',
        'is_active',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * @return HasMany<Weighing, $this>
     */
    public function weighings(): HasMany
    {
        return $this->hasMany(Weighing::class);
    }

    public function maxCapacityKg(): float
    {
        return self::kgFromStorage($this->max_capacity_kg) ?? 0.0;
    }

    public function resolutionKg(): float
    {
        return self::kgFromStorage($this->resolution_kg) ?? 0.0;
    }

    /** Verificazione periodica valida alla data (inclusa). Senza data lo strumento non e' verificato. */
    public function isVerifiedAt(CarbonImmutable $at): bool
    {
        return $this->verified_until !== null && $this->verified_until->endOfDay()->greaterThanOrEqualTo($at);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'type' => ScaleTypeEnum::class,
            'driver' => ScaleDriverEnum::class,
            'port' => 'integer',
            'max_capacity_kg' => 'decimal:3',
            'resolution_kg' => 'decimal:3',
            'verified_until' => 'immutable_date',
            'is_active' => 'boolean',
        ]);
    }
}
