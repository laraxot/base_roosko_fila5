<?php

declare(strict_types=1);

namespace Modules\Mobile\Contracts;

use Modules\Mobile\Datas\CategoryData;
use Modules\Mobile\Datas\ProductData;
use Modules\Mobile\Datas\TableData;

/**
 * Port used by Mobile to consume restaurant capabilities.
 *
 * The concrete adapter belongs to the consuming application/domain module.
 * Mobile therefore remains installable without Restaurant and without any
 * knowledge of its models, enums, or persistence layer.
 */
interface RestaurantGateway
{
    public function table(int|string $tableId): ?TableData;

    public function product(int|string $productId): ?ProductData;

    public function category(int|string $categoryId): ?CategoryData;

    /** @return list<TableData> */
    public function tables(?int $zoneId = null): array;

    /** @return list<CategoryData> */
    public function categories(): array;

    /** @param array<string, mixed> $order */
    public function createOrder(array $order): int|string;
}
