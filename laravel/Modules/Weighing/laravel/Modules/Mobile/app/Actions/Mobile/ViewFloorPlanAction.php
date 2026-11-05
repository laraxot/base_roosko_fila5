<?php

declare(strict_types=1);

namespace Modules\Mobile\Actions\Mobile;

use Modules\Mobile\Datas\TableData;
use Spatie\QueueableAction\QueueableAction;

/**
 * Action for viewing floor plan of restaurant.
 * Displays tables, zones, and seating arrangement.
 */
class ViewFloorPlanAction
{
    use QueueableAction;

    /** @return array<string, mixed> */
    public function execute(TableData $table): array
    {
        return [
            'success' => true,
            'type' => 'floor_plan',
            'data' => [
                'table' => [
                    'id' => $table->tableId,
                    'name' => $table->name,
                    'seats' => $table->capacity,
                    'status' => $table->status,
                ],
                'zone' => $table->zone,
                'capacity' => $table->capacity,
            ],
        ];
    }
}
