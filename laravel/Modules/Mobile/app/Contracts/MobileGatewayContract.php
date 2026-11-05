<?php

declare(strict_types=1);

namespace Modules\Mobile\Contracts;

use Illuminate\Support\Collection;

/**
 * Agnostic Mobile Gateway Contract.
 * Usable in any project (FixCity, restaurant, etc.).
 * @see BMAD STORY-410 · Issue #410 · Discussion #410
 */
interface MobileGatewayContract
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function reportIssue(array $data): array;

    /** @return Collection<int, array<string, mixed>> */
    public function getNearbyReports(float $lat, float $lng, int $radius): Collection;

    /** @return array<string, mixed> */
    public function getReport(int $id): array;

    public function updateStatus(int $id, string $status): bool;
}
