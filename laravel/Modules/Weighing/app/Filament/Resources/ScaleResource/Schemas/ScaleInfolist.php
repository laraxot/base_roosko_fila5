<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\ScaleResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

/**
 * Lang: Modules/Weighing/lang/{locale}/scale_infolist.php.
 */
class ScaleInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'code' => TextEntry::make('code')->copyable(),
            'name' => TextEntry::make('name'),
            'type' => TextEntry::make('type')->badge(),
            'driver' => TextEntry::make('driver')->badge(),
            'host' => TextEntry::make('host'),
            'port' => TextEntry::make('port'),
            'max_capacity_kg' => TextEntry::make('max_capacity_kg')->numeric(3)->suffix(' kg'),
            'resolution_kg' => TextEntry::make('resolution_kg')->numeric(3)->suffix(' kg'),
            'verified_until' => TextEntry::make('verified_until')->date(),
            'is_active' => IconEntry::make('is_active')->boolean(),
        ];
    }
}
