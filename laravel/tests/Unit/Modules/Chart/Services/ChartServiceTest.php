<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Chart\Models\Chart;
use Modules\Chart\Services\ChartService;
use Modules\User\Models\User;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function () {
    $this->chartService = app(ChartService::class);
    $this->user = User::factory()->create();
});

describe('ChartService', function () {
    it('creates a new chart with valid data', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'width' => 800,
            'height' => 600,
            'user_id' => $this->user->id,
        ];

        $chart = $this->chartService->createChart($chartData);

        expect($chart)->toBeInstanceOf(Chart::class);
        expect($chart->post_id)->toBe(1);
        expect($chart->type)->toBe('bar');
        expect($chart->width)->toBe(800);
        expect($chart->height)->toBe(600);
        expect($chart->user_id)->toBe($this->user->id);
    });

    it('updates an existing chart', function () {
        $chart = Chart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $updateData = [
            'type' => 'line',
            'width' => 1000,
        ];

        $updatedChart = $this->chartService->updateChart($chart->id, $updateData);

        expect($updatedChart->type)->toBe('line');
        expect($updatedChart->width)->toBe(1000);
        expect($updatedChart->height)->toBe($chart->height); // Non modificato
    });

    it('deletes a chart and returns true', function () {
        $chart = Chart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $result = $this->chartService->deleteChart($chart->id);

        expect($result)->toBeTrue();
        $this->assertDatabaseMissing('charts', ['id' => $chart->id]);
    });

    it('returns null when updating non-existent chart', function () {
        $result = $this->chartService->updateChart(999, ['type' => 'line']);

        expect($result)->toBeNull();
    });

    it('returns false when deleting non-existent chart', function () {
        $result = $this->chartService->deleteChart(999);

        expect($result)->toBeFalse();
    });

    it('gets charts by user ID', function () {
        Chart::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        Chart::factory()->count(2)->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $userCharts = $this->chartService->getChartsByUser($this->user->id);

        expect($userCharts)->toHaveCount(3);
        expect($userCharts->first())->toBeInstanceOf(Chart::class);
    });

    it('gets charts by type', function () {
        Chart::factory()->count(2)->create(['type' => 'bar']);
        Chart::factory()->count(3)->create(['type' => 'line']);

        $barCharts = $this->chartService->getChartsByType('bar');
        $lineCharts = $this->chartService->getChartsByType('line');

        expect($barCharts)->toHaveCount(2);
        expect($lineCharts)->toHaveCount(3);
    });

    it('validates chart dimensions', function () {
        $invalidData = [
            'post_id' => 1,
            'type' => 'bar',
            'width' => -100, // Dimensione negativa
            'height' => 0,   // Dimensione zero
        ];

        expect(fn () => $this->chartService->createChart($invalidData))
            ->toThrow(InvalidArgumentException::class);
    });

    it('generates chart configuration', function () {
        $chart = Chart::factory()->create([
            'type' => 'bar',
            'width' => 800,
            'height' => 600,
        ]);

        $config = $this->chartService->generateChartConfig($chart);

        expect($config)->toBeArray();
        expect($config)->toHaveKey('type');
        expect($config)->toHaveKey('dimensions');
        expect($config['type'])->toBe('bar');
        expect($config['dimensions']['width'])->toBe(800);
        expect($config['dimensions']['height'])->toBe(600);
    });

    it('exports chart data in different formats', function () {
        $chart = Chart::factory()->create([
            'type' => 'bar',
            'width' => 800,
            'height' => 600,
        ]);

        $jsonData = $this->chartService->exportChartData($chart, 'json');
        $csvData = $this->chartService->exportChartData($chart, 'csv');

        expect($jsonData)->toBeString();
        expect($csvData)->toBeString();
        expect(json_decode($jsonData, true))->toBeArray();
    });

    it('calculates chart statistics', function () {
        Chart::factory()->count(5)->create(['type' => 'bar']);
        Chart::factory()->count(3)->create(['type' => 'line']);
        Chart::factory()->count(2)->create(['type' => 'pie']);

        $stats = $this->chartService->getChartStatistics();

        expect($stats)->toBeArray();
        expect($stats)->toHaveKey('total');
        expect($stats)->toHaveKey('by_type');
        expect($stats['total'])->toBe(10);
        expect($stats['by_type']['bar'])->toBe(5);
        expect($stats['by_type']['line'])->toBe(3);
        expect($stats['by_type']['pie'])->toBe(2);
    });
});
