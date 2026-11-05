<?php

declare(strict_types=1);

namespace Modules\Mobile\Datas;

final readonly class ProductData
{
    /** @param list<string> $allergens */
    public function __construct(
        public int|string $productId,
        public string $name,
        public float $price,
        public array $allergens = [],
    ) {
    }
}
