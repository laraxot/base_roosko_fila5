<?php

declare(strict_types=1);

namespace Modules\Weighing\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Stato del ticket. Etichette: lang/{locale}/weighing_status_enum.php.
 * Un ticket chiuso non si modifica: si annulla (voided) e se ne apre un altro.
 */
enum WeighingStatusEnum: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case OPEN = 'open';
    case CLOSED = 'closed';
    case VOIDED = 'voided';

    public function canTransitionTo(self $to): bool
    {
        return match ($this) {
            self::OPEN => in_array($to, [self::CLOSED, self::VOIDED], true),
            self::CLOSED => $to === self::VOIDED,
            self::VOIDED => false,
        };
    }

    /** Pesi e ticket non sono piu' modificabili. */
    public function isLocked(): bool
    {
        return $this !== self::OPEN;
    }
}
