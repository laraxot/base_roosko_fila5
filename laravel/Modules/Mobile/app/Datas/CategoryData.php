<?php

declare(strict_types=1);

namespace Modules\Mobile\Datas;

final readonly class CategoryData
{
    /** @param list<ProductData> $products */
    public function __construct(
        public int|string $categoryId,
        public string $name,
        public array $products = [],
    ) {
    }
}
