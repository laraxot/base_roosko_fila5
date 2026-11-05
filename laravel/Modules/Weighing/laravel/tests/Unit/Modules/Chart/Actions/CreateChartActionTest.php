<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Chart\Actions\CreateChartAction;
use Modules\Chart\Models\Chart;
use Modules\User\Models\User;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class, WithFaker::class);

beforeEach(function () {
    $this->action = app(CreateChartAction::class);
    $this->user = User::factory()->create();
});

describe('CreateChartAction', function () {
    it('creates a new chart successfully', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'width' => 800,
            'height' => 600,
            'user_id' => $this->user->id,
        ];

        $chart = $this->action->execute($chartData);

        expect($chart)->toBeInstanceOf(Chart::class);
        expect($chart->post_id)->toBe(1);
        expect($chart->type)->toBe('bar');
        expect($chart->width)->toBe(800);
        expect($chart->height)->toBe(600);
        expect($chart->user_id)->toBe($this->user->id);
        expect($chart->id)->not->toBeNull();
    });

    it('validates required fields before creation', function () {
        $invalidData = [
            'type' => 'bar',
            // post_id mancante
        ];

        expect(fn () => $this->action->execute($invalidData))
            ->toThrow(InvalidArgumentException::class, 'post_id is required');
    });

    it('validates chart type is valid', function () {
        $invalidData = [
            'post_id' => 1,
            'type' => 'invalid_type',
            'width' => 800,
            'height' => 600,
        ];

        expect(fn () => $this->action->execute($invalidData))
            ->toThrow(InvalidArgumentException::class, 'Invalid chart type: invalid_type');
    });

    it('validates dimensions are positive', function () {
        $invalidData = [
            'post_id' => 1,
            'type' => 'bar',
            'width' => -100,
            'height' => 0,
        ];

        expect(fn () => $this->action->execute($invalidData))
            ->toThrow(InvalidArgumentException::class, 'Chart dimensions must be positive');
    });

    it('sets default dimensions when not provided', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            // width e height non specificati
        ];

        $chart = $this->action->execute($chartData);

        expect($chart->width)->toBe(800); // Default width
        expect($chart->height)->toBe(600); // Default height
    });

    it('generates unique chart identifier', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
        ];

        $chart1 = $this->action->execute($chartData);
        $chart2 = $this->action->execute($chartData);

        expect($chart1->id)->not->toBe($chart2->id);
        expect($chart1->chart_identifier)->not->toBe($chart2->chart_identifier);
    });

    it('sets creation timestamp', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
        ];

        $chart = $this->action->execute($chartData);

        expect($chart->created_at)->not->toBeNull();
        expect($chart->updated_at)->not->toBeNull();
    });

    it('handles large chart dimensions', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'width' => 1920,
            'height' => 1080,
        ];

        $chart = $this->action->execute($chartData);

        expect($chart->width)->toBe(1920);
        expect($chart->height)->toBe(1080);
    });

    it('creates chart with custom configuration', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'line',
            'width' => 1200,
            'height' => 800,
            'configuration' => [
                'colors' => ['#ff0000', '#00ff00'],
                'animation' => true,
            ],
        ];

        $chart = $this->action->execute($chartData);

        expect($chart->configuration)->toBe([
            'colors' => ['#ff0000', '#00ff00'],
            'animation' => true,
        ]);
    });

    it('validates configuration format', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'configuration' => 'invalid_config',
        ];

        expect(fn () => $this->action->execute($chartData))
            ->toThrow(InvalidArgumentException::class, 'Configuration must be an array');
    });

    it('creates chart with tags', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'tags' => ['analytics', 'dashboard'],
        ];

        $chart = $this->action->execute($chartData);

        expect($chart->tags)->toBe(['analytics', 'dashboard']);
    });

    it('handles empty tags array', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'tags' => [],
        ];

        $chart = $this->action->execute($chartData);

        expect($chart->tags)->toBe([]);
    });

    it('validates tags are strings', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'tags' => ['valid', 123, 'invalid'],
        ];

        expect(fn () => $this->action->execute($chartData))
            ->toThrow(InvalidArgumentException::class, 'All tags must be strings');
    });

    it('creates chart with description', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'description' => 'Chart for sales analytics',
        ];

        $chart = $this->action->execute($chartData);

        expect($chart->description)->toBe('Chart for sales analytics');
    });

    it('truncates long description', function () {
        $longDescription = str_repeat('a', 1000);
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'description' => $longDescription,
        ];

        $chart = $this->action->execute($chartData);

        expect(strlen($chart->description))->toBeLessThanOrEqual(500);
    });

    it('sets chart as active by default', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
        ];

        $chart = $this->action->execute($chartData);

        expect($chart->is_active)->toBeTrue();
    });

    it('creates chart with custom active status', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'is_active' => false,
        ];

        $chart = $this->action->execute($chartData);

        expect($chart->is_active)->toBeFalse();
    });

    it('validates boolean fields', function () {
        $chartData = [
            'post_id' => 1,
            'type' => 'bar',
            'is_active' => 'not_boolean',
        ];

        expect(fn () => $this->action->execute($chartData))
            ->toThrow(InvalidArgumentException::class, 'is_active must be a boolean');
    });
});
