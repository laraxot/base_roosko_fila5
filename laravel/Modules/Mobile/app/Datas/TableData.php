<?php

declare(strict_types=1);

namespace Modules\Mobile\Datas;

final readonly class TableData
{
    public function __construct(
        public int|string $tableId,
        public string $name,
        public int $capacity,
        public string $zone,
        public string $status,
    ) {
    }
}
