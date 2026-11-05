<?php

declare(strict_types=1);

namespace Modules\Weighing\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Weighing\Database\Factories\WeighingFactory;
use Modules\Weighing\Enums\WeighingDirectionEnum;
use Modules\Weighing\Enums\WeighingStatusEnum;
use Modules\Weighing\Exceptions\WeighingLockedException;

/**
 * Ticket di pesata (`W-{anno}-{progressivo}`), collegabile con morph `weighable` al modello di dominio
 * (in Roosko: conferimento o uscita).
 *
 * Chiuso o annullato non si modifica piu' nei pesi e nel ticket (guardia in booted()): restano
 * aggiornabili solo stato/annullamento, collegamento `weighable` e note. Non si cancella.
 *
 * @phpstan-type RawReading array{phase: string, kg: float, raw: string|null, scale_id: int|null, at: string}
 *
 * @property int $id
 * @property string $ticket_number
 * @property string|null $weighable_type
 * @property int|null $weighable_id
 * @property int|null $scale_id
 * @property WeighingDirectionEnum $direction
 * @property WeighingStatusEnum $status
 * @property string|null $vehicle_plate
 * @property string|null $trailer_plate
 * @property string|null $gross_kg
 * @property string|null $tare_kg
 * @property string|null $net_kg
 * @property CarbonImmutable|null $gross_weighed_at
 * @property CarbonImmutable|null $tare_weighed_at
 * @property CarbonImmutable|null $closed_at
 * @property CarbonImmutable|null $voided_at
 * @property string|null $void_reason
 * @property list<RawReading>|null $raw_readings
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read Scale|null $scale
 * @property-read Model|null $weighable
 *
 * @method static Builder<static>|Weighing newModelQuery()
 * @method static Builder<static>|Weighing newQuery()
 * @method static Builder<static>|Weighing query()
 * @method static WeighingFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class Weighing extends BaseModel
{
    /** Attributi ancora modificabili su un ticket chiuso o annullato. */
    public const MUTABLE_WHEN_LOCKED = [
        'status',
        'voided_at',
        'void_reason',
        'weighable_type',
        'weighable_id',
        'notes',
        'updated_at',
        'updated_by',
    ];

    /** @var list<string> */
    protected $fillable = [
        'ticket_number',
        'weighable_type',
        'weighable_id',
        'scale_id',
        'direction',
        'status',
        'vehicle_plate',
        'trailer_plate',
        'gross_kg',
        'tare_kg',
        'net_kg',
        'gross_weighed_at',
        'tare_weighed_at',
        'closed_at',
        'voided_at',
        'void_reason',
        'raw_readings',
        'notes',
    ];

    /**
     * @return BelongsTo<Scale, $this>
     */
    public function scale(): BelongsTo
    {
        return $this->belongsTo(Scale::class)->withTrashed();
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function weighable(): MorphTo
    {
        return $this->morphTo();
    }

    public function grossKg(): ?float
    {
        return self::kgFromStorage($this->gross_kg);
    }

    public function tareKg(): ?float
    {
        return self::kgFromStorage($this->tare_kg);
    }

    public function netKg(): ?float
    {
        return self::kgFromStorage($this->net_kg);
    }

    /** Peso della prima pesata (lordo per inbound/internal, tara per outbound). */
    public function firstKg(): ?float
    {
        return $this->direction->firstWeightIsGross() ? $this->grossKg() : $this->tareKg();
    }

    protected static function booted(): void
    {
        static::updating(static function (Weighing $weighing): void {
            $raw = $weighing->getRawOriginal('status');
            $original = is_string($raw) ? WeighingStatusEnum::tryFrom($raw) : null;
            if ($original === null || ! $original->isLocked()) {
                return;
            }

            $forbidden = array_values(array_diff(array_keys($weighing->getDirty()), self::MUTABLE_WHEN_LOCKED));
            if ($forbidden !== []) {
                throw WeighingLockedException::for($weighing, $forbidden);
            }
        });

        static::deleting(static function (Weighing $weighing): void {
            if ($weighing->status->isLocked()) {
                throw WeighingLockedException::for($weighing);
            }
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'weighable_id' => 'integer',
            'scale_id' => 'integer',
            'direction' => WeighingDirectionEnum::class,
            'status' => WeighingStatusEnum::class,
            'gross_kg' => 'decimal:3',
            'tare_kg' => 'decimal:3',
            'net_kg' => 'decimal:3',
            'gross_weighed_at' => 'immutable_datetime',
            'tare_weighed_at' => 'immutable_datetime',
            'closed_at' => 'immutable_datetime',
            'voided_at' => 'immutable_datetime',
            'raw_readings' => 'array',
        ]);
    }
}
