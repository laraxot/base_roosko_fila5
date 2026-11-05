<?php

declare(strict_types=1);

namespace Modules\Weighing\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Tipo di strumento. Etichette: lang/{locale}/scale_type_enum.php.
 */
enum ScaleTypeEnum: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case WEIGHBRIDGE = 'weighbridge';
    case PLATFORM = 'platform';
    case CRANE = 'crane';
    case FORKLIFT = 'forklift';
}
