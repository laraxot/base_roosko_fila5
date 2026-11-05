<?php

declare(strict_types=1);

namespace Modules\Weighing\Filament\Resources\WeighingResource\Schemas;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Weighing\Models\Weighing;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

/**
 * Lang: Modules/Weighing/lang/{locale}/weighing_infolist.php.
 */
class WeighingInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'ticket_number' => TextEntry::make('ticket_number')->copyable(),
            'status' => TextEntry::make('status')->badge(),
            'direction' => TextEntry::make('direction')->badge(),
            'scale' => TextEntry::make('scale.name'),
            'vehicle_plate' => TextEntry::make('vehicle_plate'),
            'trailer_plate' => TextEntry::make('trailer_plate'),
            'gross_kg' => TextEntry::make('gross_kg')->numeric(3)->suffix(' kg'),
            'tare_kg' => TextEntry::make('tare_kg')->numeric(3)->suffix(' kg'),
            'net_kg' => TextEntry::make('net_kg')->numeric(3)->suffix(' kg')->weight('bold'),
            'gross_weighed_at' => TextEntry::make('gross_weighed_at')->dateTime(),
            'tare_weighed_at' => TextEntry::make('tare_weighed_at')->dateTime(),
            'closed_at' => TextEntry::make('closed_at')->dateTime(),
            'weighable_type' => TextEntry::make('weighable_type')
                ->formatStateUsing(static fn (?string $state): string => $state === null ? '—' : class_basename($state)),
            'weighable_id' => TextEntry::make('weighable_id'),
            'voided_at' => TextEntry::make('voided_at')->dateTime()
                ->visible(static fn (Weighing $record): bool => $record->voided_at !== null),
            'void_reason' => TextEntry::make('void_reason')
                ->visible(static fn (Weighing $record): bool => $record->voided_at !== null),
            'notes' => TextEntry::make('notes')->columnSpanFull(),
            'raw_readings' => KeyValueEntry::make('raw_readings')
                ->state(static fn (Weighing $record): array => self::readings($record))
                ->columnSpanFull(),
        ];
    }

    /**
     * Una riga per lettura: "fase @ data" => "kg (riga grezza)".
     *
     * @return array<string, string>
     */
    private static function readings(Weighing $record): array
    {
        $rows = [];
        foreach ($record->raw_readings ?? [] as $index => $reading) {
            $key = sprintf('%d. %s @ %s', $index + 1, $reading['phase'], $reading['at']);
            $rows[$key] = number_format($reading['kg'], 3, ',', '.').' kg'.($reading['raw'] !== null ? ' ('.trim($reading['raw']).')' : '');
        }

        return $rows;
    }
}
