<?php

declare(strict_types=1);

namespace Modules\Weighing\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Verso della pesata. Etichette: lang/{locale}/weighing_direction_enum.php.
 *
 * - inbound: il mezzo entra carico, prima pesata = lordo, seconda = tara;
 * - outbound: il mezzo entra vuoto, prima pesata = tara, seconda = lordo;
 * - internal: movimentazione interna, come inbound (prima = lordo).
 */
enum WeighingDirectionEnum: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case INBOUND = 'inbound';
    case OUTBOUND = 'outbound';
    case INTERNAL = 'internal';

    public function firstWeightIsGross(): bool
    {
        return $this !== self::OUTBOUND;
    }
}
