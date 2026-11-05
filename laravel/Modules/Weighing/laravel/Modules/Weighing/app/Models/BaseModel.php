<?php

declare(strict_types=1);

namespace Modules\Weighing\Models;

use Modules\Xot\Models\XotBaseModel;

/**
 * Base dei modelli Weighing: connessione `weighing`, chiavi bigIncrements (id intero).
 *
 * Pesi in kg `decimal(14,3)` con cast `decimal:3` (Laravel li restituisce come stringa numerica):
 * le Actions li leggono e scrivono tramite {@see self::kgToStorage()} / {@see self::kgFromStorage()}.
 */
abstract class BaseModel extends XotBaseModel
{
    /** @var string */
    protected $connection = 'weighing';

    /** Formato di scrittura di un peso in kg (3 decimali, punto decimale). */
    public static function kgToStorage(float $kg): string
    {
        return number_format(round($kg, 3), 3, '.', '');
    }

    public static function kgFromStorage(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? round((float) $value, 3) : null;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'id' => 'integer',
        ]);
    }
}
