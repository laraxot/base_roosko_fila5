<?php

declare(strict_types=1);

namespace Modules\Weighing\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Weighing\Contracts\ScaleDriverContract;
use Modules\Weighing\Drivers\FakeScaleDriver;
use Modules\Weighing\Drivers\ManualScaleDriver;
use Modules\Weighing\Drivers\TcpAsciiScaleDriver;
use Modules\Xot\Traits\EnumTrait;

/**
 * Come si legge la bilancia. Etichette: lang/{locale}/scale_driver_enum.php.
 */
enum ScaleDriverEnum: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case MANUAL = 'manual';
    case TCP_ASCII = 'tcp_ascii';
    case FAKE = 'fake';

    /** @return class-string<ScaleDriverContract> */
    public function driverClass(): string
    {
        return match ($this) {
            self::MANUAL => ManualScaleDriver::class,
            self::TCP_ASCII => TcpAsciiScaleDriver::class,
            self::FAKE => FakeScaleDriver::class,
        };
    }

    public function requiresHost(): bool
    {
        return $this === self::TCP_ASCII;
    }

    /** Con il driver manuale il peso si digita: non c'e' niente da leggere. */
    public function canRead(): bool
    {
        return $this !== self::MANUAL;
    }
}
