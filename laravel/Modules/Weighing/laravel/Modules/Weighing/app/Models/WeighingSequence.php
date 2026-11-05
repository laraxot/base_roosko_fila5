<?php

declare(strict_types=1);

namespace Modules\Weighing\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Weighing\Database\Factories\WeighingSequenceFactory;

/**
 * Contatore annuale dei ticket di pesata. La riga dell'anno si blocca con `lockForUpdate` dentro la
 * transazione che crea la pesata: bloccare righe di `weighings` non basterebbe per il primo ticket
 * dell'anno (non c'e' nessuna riga da bloccare).
 *
 * @property int $id
 * @property int $year
 * @property int $last_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<static>|WeighingSequence newModelQuery()
 * @method static Builder<static>|WeighingSequence newQuery()
 * @method static Builder<static>|WeighingSequence query()
 * @method static WeighingSequenceFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class WeighingSequence extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'year',
        'last_number',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'year' => 'integer',
            'last_number' => 'integer',
        ]);
    }
}
