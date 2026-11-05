<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Chart\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Chart\Models\BaseModel;
use Modules\Chart\Models\Chart;
use Tests\TestCase;

class ChartTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected Chart $chart;

    protected function setUp(): void
    {
        parent::setUp();
        $this->chart = new Chart;
    }

    /** @test */
    public function it_extends_base_model(): void
    {
        $this->assertInstanceOf(BaseModel::class, $this->chart);
    }

    /** @test */
    public function it_has_correct_fillable_attributes(): void
    {
        $expectedFillable = [
            'id',
            'post_id',
            'post_type',
            'type',
            'width', 'height',
            'color',
            'bg_color',
            'font_family',
            'font_size',
            'font_style',
            'y_grace',
            'yaxis_hide',
            'list_color',
            'grace',
            'x_label_angle',
            'show_box',
            'x_label_margin',
            'plot_perc_width',
            'plot_value_show',
            'plot_value_format',
            'plot_value_pos',
            'plot_value_color',
            'group_by',
            'sort_by',
            'transparency',
            'colors',
        ];

        $this->assertEquals($expectedFillable, $this->chart->getFillable());
    }

    /** @test */
    public function it_has_correct_default_attributes(): void
    {
        $expectedDefaults = [
            'list_color' => '#d60021',
            'color' => '#d60021',
            'font_family' => 15,
            'font_style' => 9002,
            'font_size' => 12,
            'x_label_angle' => 0,
            'show_box' => false,
            'x_label_margin' => 10,
            'plot_perc_width' => 90,
            'plot_value_show' => 1,
            'plot_value_pos' => 1,
            'plot_value_color' => '#000000',
        ];

        $this->assertEquals($expectedDefaults, $this->chart->getAttributes());
    }

    /** @test */
    public function it_can_create_chart_with_valid_data(): void
    {
        $chartData = [
            'post_id' => '123',
            'post_type' => 'page',
            'type' => 'line',
            'width' => 800,
            'height' => 600,
            'color' => '#ff0000',
            'bg_color' => '#ffffff',
        ];

        $chart = Chart::create($chartData);

        $this->assertInstanceOf(Chart::class, $chart);
        $this->assertEquals('123', $chart->post_id);
        $this->assertEquals('page', $chart->post_type);
        $this->assertEquals('line', $chart->type);
        $this->assertEquals(800, $chart->width);
        $this->assertEquals(600, $chart->height);
        $this->assertEquals('#ff0000', $chart->color);
        $this->assertEquals('#ffffff', $chart->bg_color);
    }

    /** @test */
    public function it_uses_default_values_when_attributes_not_provided(): void
    {
        $chart = Chart::create([
            'post_id' => '456',
            'post_type' => 'post',
        ]);

        $this->assertEquals('#d60021', $chart->list_color);
        $this->assertEquals('#d60021', $chart->color);
        $this->assertEquals(15, $chart->font_family);
        $this->assertEquals(9002, $chart->font_style);
        $this->assertEquals(12, $chart->font_size);
        $this->assertEquals(0, $chart->x_label_angle);
        $this->assertFalse($chart->show_box);
        $this->assertEquals(10, $chart->x_label_margin);
        $this->assertEquals(90, $chart->plot_perc_width);
        $this->assertEquals(1, $chart->plot_value_show);
        $this->assertEquals(1, $chart->plot_value_pos);
        $this->assertEquals('#000000', $chart->plot_value_color);
    }

    /** @test */
    public function it_can_update_chart_attributes(): void
    {
        $chart = Chart::create([
            'post_id' => '789',
            'post_type' => 'post',
            'type' => 'bar',
        ]);

        $chart->update([
            'type' => 'pie',
            'width' => 1000,
            'height' => 800,
            'color' => '#00ff00',
        ]);

        $this->assertEquals('pie', $chart->fresh()->type);
        $this->assertEquals(1000, $chart->fresh()->width);
        $this->assertEquals(800, $chart->fresh()->height);
        $this->assertEquals('#00ff00', $chart->fresh()->color);
    }

    /** @test */
    public function it_can_handle_nullable_attributes(): void
    {
        $chart = Chart::create([
            'post_id' => '999',
            'post_type' => 'post',
            'width' => null,
            'height' => null,
            'color' => null,
        ]);

        $this->assertNull($chart->width);
        $this->assertNull($chart->height);
        $this->assertNull($chart->color);
    }

    /** @test */
    public function it_can_handle_array_attributes(): void
    {
        $colorsArray = [
            'primary' => '#007bff',
            'secondary' => '#6c757d',
            'success' => '#28a745',
        ];

        $chart = Chart::create([
            'post_id' => '111',
            'post_type' => 'post',
            'colors' => $colorsArray,
        ]);

        $this->assertEquals($colorsArray, $chart->colors);
    }

    /** @test */
    public function it_can_handle_boolean_attributes(): void
    {
        $chart = Chart::create([
            'post_id' => '222',
            'post_type' => 'post',
            'show_box' => true,
            'yaxis_hide' => true,
        ]);

        $this->assertTrue($chart->show_box);
        $this->assertTrue($chart->yaxis_hide);
    }

    /** @test */
    public function it_can_handle_integer_attributes(): void
    {
        $chart = Chart::create([
            'post_id' => '333',
            'post_type' => 'post',
            'font_family' => 20,
            'font_size' => 16,
            'font_style' => 9001,
            'y_grace' => 5,
            'grace' => 10,
            'x_label_angle' => 45,
            'x_label_margin' => 15,
            'plot_perc_width' => 95,
            'plot_value_show' => 0,
            'plot_value_pos' => 2,
            'transparency' => 50,
        ]);

        $this->assertEquals(20, $chart->font_family);
        $this->assertEquals(16, $chart->font_size);
        $this->assertEquals(9001, $chart->font_style);
        $this->assertEquals(5, $chart->y_grace);
        $this->assertEquals(10, $chart->grace);
        $this->assertEquals(45, $chart->x_label_angle);
        $this->assertEquals(15, $chart->x_label_margin);
        $this->assertEquals(95, $chart->plot_perc_width);
        $this->assertEquals(0, $chart->plot_value_show);
        $this->assertEquals(2, $chart->plot_value_pos);
        $this->assertEquals(50, $chart->transparency);
    }

    /** @test */
    public function it_can_handle_string_attributes(): void
    {
        $chart = Chart::create([
            'post_id' => '444',
            'post_type' => 'post',
            'type' => 'scatter',
            'group_by' => 'category',
            'sort_by' => 'value',
            'plot_value_format' => 'currency',
        ]);

        $this->assertEquals('scatter', $chart->type);
        $this->assertEquals('category', $chart->group_by);
        $this->assertEquals('value', $chart->sort_by);
        $this->assertEquals('currency', $chart->plot_value_format);
    }

    /** @test */
    public function it_can_be_created_with_factory(): void
    {
        $chart = Chart::factory()->create();

        $this->assertInstanceOf(Chart::class, $chart);
        $this->assertNotEmpty($chart->post_id);
        $this->assertNotEmpty($chart->post_type);
        $this->assertNotEmpty($chart->type);
    }

    /** @test */
    public function it_can_be_created_with_factory_and_specific_attributes(): void
    {
        $chart = Chart::factory()->create([
            'type' => 'doughnut',
            'width' => 1200,
            'height' => 900,
        ]);

        $this->assertEquals('doughnut', $chart->type);
        $this->assertEquals(1200, $chart->width);
        $this->assertEquals(900, $chart->height);
    }

    /** @test */
    public function it_can_be_created_multiple_times_with_factory(): void
    {
        $charts = Chart::factory()->count(5)->create();

        $this->assertCount(5, $charts);
        $this->assertContainsOnlyInstancesOf(Chart::class, $charts);
    }

    /** @test */
    public function it_can_be_created_with_factory_states(): void
    {
        $chart = Chart::factory()->line()->create();

        $this->assertEquals('line', $chart->type);
    }

    /** @test */
    public function it_can_be_created_with_factory_relationships(): void
    {
        $chart = Chart::factory()
            ->has(Post::factory())
            ->create();

        $this->assertInstanceOf(Post::class, $chart->post);
    }

    /** @test */
    public function it_has_correct_table_name(): void
    {
        $this->assertEquals('charts', $this->chart->getTable());
    }

    /** @test */
    public function it_has_correct_primary_key(): void
    {
        $this->assertEquals('id', $this->chart->getKeyName());
    }

    /** @test */
    public function it_uses_incrementing_primary_key(): void
    {
        $this->assertTrue($this->chart->getIncrementing());
    }

    /** @test */
    public function it_uses_timestamps(): void
    {
        $this->assertTrue($this->chart->usesTimestamps());
    }

    /** @test */
    public function it_has_created_at_and_updated_at_columns(): void
    {
        $chart = Chart::create([
            'post_id' => '555',
            'post_type' => 'post',
        ]);

        $this->assertNotNull($chart->created_at);
        $this->assertNotNull($chart->updated_at);
    }

    /** @test */
    public function it_can_be_soft_deleted_if_enabled(): void
    {
        // Verifica se il modello supporta soft deletes
        if (method_exists($this->chart, 'trashed')) {
            $chart = Chart::create([
                'post_id' => '666',
                'post_type' => 'post',
            ]);

            $chart->delete();

            $this->assertSoftDeleted($chart);
        } else {
            $this->markTestSkipped('Soft deletes not enabled for Chart model');
        }
    }

    /** @test */
    public function it_can_be_restored_if_soft_deletes_enabled(): void
    {
        // Verifica se il modello supporta soft deletes
        if (method_exists($this->chart, 'trashed')) {
            $chart = Chart::create([
                'post_id' => '777',
                'post_type' => 'post',
            ]);

            $chart->delete();
            $chart->restore();

            $this->assertNotSoftDeleted($chart);
        } else {
            $this->markTestSkipped('Soft deletes not enabled for Chart model');
        }
    }

    /** @test */
    public function it_can_be_permanently_deleted(): void
    {
        $chart = Chart::create([
            'post_id' => '888',
            'post_type' => 'post',
        ]);

        $chartId = $chart->id;
        $chart->forceDelete();

        $this->assertDatabaseMissing('charts', ['id' => $chartId]);
    }

    /** @test */
    public function it_can_be_found_by_id(): void
    {
        $chart = Chart::create([
            'post_id' => '999',
            'post_type' => 'post',
        ]);

        $foundChart = Chart::find($chart->id);

        $this->assertInstanceOf(Chart::class, $foundChart);
        $this->assertEquals($chart->id, $foundChart->id);
    }

    /** @test */
    public function it_can_be_found_by_post_id(): void
    {
        $chart = Chart::create([
            'post_id' => 'unique_post_123',
            'post_type' => 'post',
        ]);

        $foundChart = Chart::where('post_id', 'unique_post_123')->first();

        $this->assertInstanceOf(Chart::class, $foundChart);
        $this->assertEquals('unique_post_123', $foundChart->post_id);
    }

    /** @test */
    public function it_can_be_found_by_post_type(): void
    {
        $chart = Chart::create([
            'post_id' => '111',
            'post_type' => 'custom_type',
        ]);

        $foundCharts = Chart::where('post_type', 'custom_type')->get();

        $this->assertCount(1, $foundCharts);
        $this->assertEquals('custom_type', $foundCharts->first()->post_type);
    }

    /** @test */
    public function it_can_be_found_by_chart_type(): void
    {
        $chart = Chart::create([
            'post_id' => '222',
            'post_type' => 'post',
            'type' => 'radar',
        ]);

        $foundCharts = Chart::where('type', 'radar')->get();

        $this->assertCount(1, $foundCharts);
        $this->assertEquals('radar', $foundCharts->first()->type);
    }

    /** @test */
    public function it_can_be_filtered_by_multiple_criteria(): void
    {
        $chart1 = Chart::create([
            'post_id' => '333',
            'post_type' => 'page',
            'type' => 'line',
            'width' => 800,
        ]);

        $chart2 = Chart::create([
            'post_id' => '444',
            'post_type' => 'page',
            'type' => 'line',
            'width' => 1000,
        ]);

        $foundCharts = Chart::where('post_type', 'page')
            ->where('type', 'line')
            ->where('width', '>=', 900)
            ->get();

        $this->assertCount(1, $foundCharts);
        $this->assertEquals('444', $foundCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_ordered_by_attributes(): void
    {
        $chart1 = Chart::create([
            'post_id' => '555',
            'post_type' => 'post',
            'width' => 600,
        ]);

        $chart2 = Chart::create([
            'post_id' => '666',
            'post_type' => 'post',
            'width' => 800,
        ]);

        $chart3 = Chart::create([
            'post_id' => '777',
            'post_type' => 'post',
            'width' => 400,
        ]);

        $orderedCharts = Chart::orderBy('width', 'asc')->get();

        $this->assertEquals(400, $orderedCharts[0]->width);
        $this->assertEquals(600, $orderedCharts[1]->width);
        $this->assertEquals(800, $orderedCharts[2]->width);
    }

    /** @test */
    public function it_can_be_limited_in_results(): void
    {
        Chart::factory()->count(10)->create();

        $limitedCharts = Chart::limit(5)->get();

        $this->assertCount(5, $limitedCharts);
    }

    /** @test */
    public function it_can_be_paginated(): void
    {
        Chart::factory()->count(25)->create();

        $paginatedCharts = Chart::paginate(10);

        $this->assertEquals(25, $paginatedCharts->total());
        $this->assertEquals(10, $paginatedCharts->perPage());
        $this->assertEquals(3, $paginatedCharts->lastPage());
    }

    /** @test */
    public function it_can_be_counted(): void
    {
        Chart::factory()->count(7)->create();

        $count = Chart::count();

        $this->assertEquals(7, $count);
    }

    /** @test */
    public function it_can_be_summed_by_numeric_attribute(): void
    {
        Chart::create(['post_id' => '1', 'post_type' => 'post', 'width' => 100]);
        Chart::create(['post_id' => '2', 'post_type' => 'post', 'width' => 200]);
        Chart::create(['post_id' => '3', 'post_type' => 'post', 'width' => 300]);

        $totalWidth = Chart::sum('width');

        $this->assertEquals(600, $totalWidth);
    }

    /** @test */
    public function it_can_be_averaged_by_numeric_attribute(): void
    {
        Chart::create(['post_id' => '1', 'post_type' => 'post', 'width' => 100]);
        Chart::create(['post_id' => '2', 'post_type' => 'post', 'width' => 200]);
        Chart::create(['post_id' => '3', 'post_type' => 'post', 'width' => 300]);

        $averageWidth = Chart::avg('width');

        $this->assertEquals(200, $averageWidth);
    }

    /** @test */
    public function it_can_be_grouped_by_attribute(): void
    {
        Chart::create(['post_id' => '1', 'post_type' => 'post', 'type' => 'line']);
        Chart::create(['post_id' => '2', 'post_type' => 'post', 'type' => 'line']);
        Chart::create(['post_id' => '3', 'post_type' => 'page', 'type' => 'bar']);
        Chart::create(['post_id' => '4', 'post_type' => 'page', 'type' => 'bar']);

        $groupedCharts = Chart::selectRaw('post_type, COUNT(*) as count')
            ->groupBy('post_type')
            ->get();

        $this->assertCount(2, $groupedCharts);

        $postType = $groupedCharts->where('post_type', 'post')->first();
        $pageType = $groupedCharts->where('post_type', 'page')->first();

        $this->assertEquals(2, $postType->count);
        $this->assertEquals(2, $pageType->count);
    }

    /** @test */
    public function it_can_be_created_with_mass_assignment(): void
    {
        $chartData = [
            'post_id' => 'mass_123',
            'post_type' => 'post',
            'type' => 'area',
            'width' => 900,
            'height' => 700,
        ];

        $chart = Chart::create($chartData);

        foreach ($chartData as $key => $value) {
            $this->assertEquals($value, $chart->$key);
        }
    }

    /** @test */
    public function it_can_be_updated_with_mass_assignment(): void
    {
        $chart = Chart::create([
            'post_id' => 'mass_update_123',
            'post_type' => 'post',
            'type' => 'line',
        ]);

        $updateData = [
            'type' => 'scatter',
            'width' => 1100,
            'height' => 800,
        ];

        $chart->update($updateData);

        foreach ($updateData as $key => $value) {
            $this->assertEquals($value, $chart->fresh()->$key);
        }
    }

    /** @test */
    public function it_can_be_filled_with_attributes(): void
    {
        $chart = new Chart;

        $chart->fill([
            'post_id' => 'fill_123',
            'post_type' => 'post',
            'type' => 'doughnut',
        ]);

        $this->assertEquals('fill_123', $chart->post_id);
        $this->assertEquals('post', $chart->post_type);
        $this->assertEquals('doughnut', $chart->type);
    }

    /** @test */
    public function it_can_be_force_filled_with_attributes(): void
    {
        $chart = new Chart;

        $chart->forceFill([
            'post_id' => 'force_fill_123',
            'post_type' => 'post',
            'type' => 'polar',
        ]);

        $this->assertEquals('force_fill_123', $chart->post_id);
        $this->assertEquals('post', $chart->post_type);
        $this->assertEquals('polar', $chart->type);
    }

    /** @test */
    public function it_can_be_duplicated(): void
    {
        $originalChart = Chart::create([
            'post_id' => 'duplicate_123',
            'post_type' => 'post',
            'type' => 'line',
            'width' => 800,
            'height' => 600,
        ]);

        $duplicateChart = $originalChart->replicate();
        $duplicateChart->post_id = 'duplicate_456';
        $duplicateChart->save();

        $this->assertEquals($originalChart->type, $duplicateChart->type);
        $this->assertEquals($originalChart->width, $duplicateChart->width);
        $this->assertEquals($originalChart->height, $duplicateChart->height);
        $this->assertNotEquals($originalChart->post_id, $duplicateChart->post_id);
    }

    /** @test */
    public function it_can_be_refreshed_from_database(): void
    {
        $chart = Chart::create([
            'post_id' => 'refresh_123',
            'post_type' => 'post',
            'type' => 'line',
        ]);

        // Modifica direttamente nel database
        Chart::where('id', $chart->id)->update(['type' => 'bar']);

        $chart->refresh();

        $this->assertEquals('bar', $chart->type);
    }

    /** @test */
    public function it_can_be_touched_to_update_timestamps(): void
    {
        $chart = Chart::create([
            'post_id' => 'touch_123',
            'post_type' => 'post',
        ]);

        $originalUpdatedAt = $chart->updated_at;

        sleep(1); // Assicura che il timestamp sia diverso
        $chart->touch();

        $this->assertNotEquals($originalUpdatedAt, $chart->fresh()->updated_at);
    }

    /** @test */
    public function it_can_be_queried_with_where_in(): void
    {
        $chart1 = Chart::create(['post_id' => 'where_in_1', 'post_type' => 'post']);
        $chart2 = Chart::create(['post_id' => 'where_in_2', 'post_type' => 'post']);
        $chart3 = Chart::create(['post_id' => 'where_in_3', 'post_type' => 'post']);

        $foundCharts = Chart::whereIn('post_id', ['where_in_1', 'where_in_3'])->get();

        $this->assertCount(2, $foundCharts);
        $this->assertTrue($foundCharts->contains('post_id', 'where_in_1'));
        $this->assertTrue($foundCharts->contains('post_id', 'where_in_3'));
    }

    /** @test */
    public function it_can_be_queried_with_where_between(): void
    {
        Chart::create(['post_id' => 'between_1', 'post_type' => 'post', 'width' => 100]);
        Chart::create(['post_id' => 'between_2', 'post_type' => 'post', 'width' => 500]);
        Chart::create(['post_id' => 'between_3', 'post_type' => 'post', 'width' => 1000]);

        $foundCharts = Chart::whereBetween('width', [200, 800])->get();

        $this->assertCount(1, $foundCharts);
        $this->assertEquals('between_2', $foundCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_queried_with_where_null(): void
    {
        Chart::create(['post_id' => 'null_1', 'post_type' => 'post', 'width' => null]);
        Chart::create(['post_id' => 'null_2', 'post_type' => 'post', 'width' => 800]);

        $nullWidthCharts = Chart::whereNull('width')->get();

        $this->assertCount(1, $nullWidthCharts);
        $this->assertEquals('null_1', $nullWidthCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_queried_with_where_not_null(): void
    {
        Chart::create(['post_id' => 'not_null_1', 'post_type' => 'post', 'width' => null]);
        Chart::create(['post_id' => 'not_null_2', 'post_type' => 'post', 'width' => 800]);

        $notNullWidthCharts = Chart::whereNotNull('width')->get();

        $this->assertCount(1, $notNullWidthCharts);
        $this->assertEquals('not_null_2', $notNullWidthCharts->first()->post_id);
    }
}
