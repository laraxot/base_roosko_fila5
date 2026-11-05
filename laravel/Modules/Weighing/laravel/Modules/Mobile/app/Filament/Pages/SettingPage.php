<?php

declare(strict_types=1);

namespace Modules\Mobile\Filament\Pages;

use Filament\Forms\Components\Toggle;
use Modules\Mobile\Datas\MobileConfigData;
use Modules\Xot\Filament\Pages\BaseSettingPage;

/**
 * Impostazioni di Mobile (`config/{tenant}/mobile.php`), solo super-admin.
 *
 * Form configurato con i campi esposti da MobileConfigData.
 *
 * @see MobileConfigData
 */
final class SettingPage extends BaseSettingPage
{
    /**
     * @return array<string, Toggle>
     */
    protected function getFormSchema(): array
    {
        return [
            'enabled' => Toggle::make('enabled')->default(true)->required(),
        ];
    }
}
