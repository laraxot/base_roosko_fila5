<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Chart\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Chart\Models\BaseModel;
use Modules\Chart\Models\MixedChart;
use Tests\TestCase;

class MixedChartTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected MixedChart $mixedChart;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mixedChart = new MixedChart;
    }

    /** @test */
    public function it_extends_base_model(): void
    {
        $this->assertInstanceOf(BaseModel::class, $this->mixedChart);
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

        $this->assertEquals($expectedFillable, $this->mixedChart->getFillable());
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

        $this->assertEquals($expectedDefaults, $this->mixedChart->getAttributes());
    }

    /** @test */
    public function it_can_create_mixed_chart_with_valid_data(): void
    {
        $chartData = [
            'post_id' => 'mixed_123',
            'post_type' => 'page',
            'type' => 'mixed',
            'width' => 1000,
            'height' => 800,
            'color' => '#ff6600',
            'bg_color' => '#f8f9fa',
        ];

        $mixedChart = MixedChart::create($chartData);

        $this->assertInstanceOf(MixedChart::class, $mixedChart);
        $this->assertEquals('mixed_123', $mixedChart->post_id);
        $this->assertEquals('page', $mixedChart->post_type);
        $this->assertEquals('mixed', $mixedChart->type);
        $this->assertEquals(1000, $mixedChart->width);
        $this->assertEquals(800, $mixedChart->height);
        $this->assertEquals('#ff6600', $mixedChart->color);
        $this->assertEquals('#f8f9fa', $mixedChart->bg_color);
    }

    /** @test */
    public function it_uses_default_values_when_attributes_not_provided(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'mixed_456',
            'post_type' => 'post',
        ]);

        $this->assertEquals('#d60021', $mixedChart->list_color);
        $this->assertEquals('#d60021', $mixedChart->color);
        $this->assertEquals(15, $mixedChart->font_family);
        $this->assertEquals(9002, $mixedChart->font_style);
        $this->assertEquals(12, $mixedChart->font_size);
        $this->assertEquals(0, $mixedChart->x_label_angle);
        $this->assertFalse($mixedChart->show_box);
        $this->assertEquals(10, $mixedChart->x_label_margin);
        $this->assertEquals(90, $mixedChart->plot_perc_width);
        $this->assertEquals(1, $mixedChart->plot_value_show);
        $this->assertEquals(1, $mixedChart->plot_value_pos);
        $this->assertEquals('#000000', $mixedChart->plot_value_color);
    }

    /** @test */
    public function it_can_update_mixed_chart_attributes(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'mixed_789',
            'post_type' => 'post',
            'type' => 'mixed',
        ]);

        $mixedChart->update([
            'type' => 'combo',
            'width' => 1200,
            'height' => 900,
            'color' => '#00ff00',
        ]);

        $this->assertEquals('combo', $mixedChart->fresh()->type);
        $this->assertEquals(1200, $mixedChart->fresh()->width);
        $this->assertEquals(900, $mixedChart->fresh()->height);
        $this->assertEquals('#00ff00', $mixedChart->fresh()->color);
    }

    /** @test */
    public function it_can_handle_nullable_attributes(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'mixed_999',
            'post_type' => 'post',
            'width' => null,
            'height' => null,
            'color' => null,
        ]);

        $this->assertNull($mixedChart->width);
        $this->assertNull($mixedChart->height);
        $this->assertNull($mixedChart->color);
    }

    /** @test */
    public function it_can_handle_array_attributes(): void
    {
        $colorsArray = [
            'primary' => '#007bff',
            'secondary' => '#6c757d',
            'success' => '#28a745',
            'warning' => '#ffc107',
            'danger' => '#dc3545',
        ];

        $mixedChart = MixedChart::create([
            'post_id' => 'mixed_111',
            'post_type' => 'post',
            'colors' => $colorsArray,
        ]);

        $this->assertEquals($colorsArray, $mixedChart->colors);
    }

    /** @test */
    public function it_can_handle_boolean_attributes(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'mixed_222',
            'post_type' => 'post',
            'show_box' => true,
            'yaxis_hide' => true,
        ]);

        $this->assertTrue($mixedChart->show_box);
        $this->assertTrue($mixedChart->yaxis_hide);
    }

    /** @test */
    public function it_can_handle_integer_attributes(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'mixed_333',
            'post_type' => 'post',
            'font_family' => 25,
            'font_size' => 18,
            'font_style' => 9003,
            'y_grace' => 8,
            'grace' => 15,
            'x_label_angle' => 30,
            'x_label_margin' => 20,
            'plot_perc_width' => 85,
            'plot_value_show' => 0,
            'plot_value_pos' => 3,
            'transparency' => 75,
        ]);

        $this->assertEquals(25, $mixedChart->font_family);
        $this->assertEquals(18, $mixedChart->font_size);
        $this->assertEquals(9003, $mixedChart->font_style);
        $this->assertEquals(8, $mixedChart->y_grace);
        $this->assertEquals(15, $mixedChart->grace);
        $this->assertEquals(30, $mixedChart->x_label_angle);
        $this->assertEquals(20, $mixedChart->x_label_margin);
        $this->assertEquals(85, $mixedChart->plot_perc_width);
        $this->assertEquals(0, $mixedChart->plot_value_show);
        $this->assertEquals(3, $mixedChart->plot_value_pos);
        $this->assertEquals(75, $mixedChart->transparency);
    }

    /** @test */
    public function it_can_handle_string_attributes(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'mixed_444',
            'post_type' => 'post',
            'type' => 'mixed',
            'group_by' => 'month',
            'sort_by' => 'date',
            'plot_value_format' => 'percentage',
        ]);

        $this->assertEquals('mixed', $mixedChart->type);
        $this->assertEquals('month', $mixedChart->group_by);
        $this->assertEquals('date', $mixedChart->sort_by);
        $this->assertEquals('percentage', $mixedChart->plot_value_format);
    }

    /** @test */
    public function it_can_be_created_with_factory(): void
    {
        $mixedChart = MixedChart::factory()->create();

        $this->assertInstanceOf(MixedChart::class, $mixedChart);
        $this->assertNotEmpty($mixedChart->post_id);
        $this->assertNotEmpty($mixedChart->post_type);
        $this->assertNotEmpty($mixedChart->type);
    }

    /** @test */
    public function it_can_be_created_with_factory_and_specific_attributes(): void
    {
        $mixedChart = MixedChart::factory()->create([
            'type' => 'mixed',
            'width' => 1400,
            'height' => 1000,
        ]);

        $this->assertEquals('mixed', $mixedChart->type);
        $this->assertEquals(1400, $mixedChart->width);
        $this->assertEquals(1000, $mixedChart->height);
    }

    /** @test */
    public function it_can_be_created_multiple_times_with_factory(): void
    {
        $mixedCharts = MixedChart::factory()->count(7)->create();

        $this->assertCount(7, $mixedCharts);
        $this->assertContainsOnlyInstancesOf(MixedChart::class, $mixedCharts);
    }

    /** @test */
    public function it_can_be_created_with_factory_states(): void
    {
        $mixedChart = MixedChart::factory()->mixed()->create();

        $this->assertEquals('mixed', $mixedChart->type);
    }

    /** @test */
    public function it_can_be_created_with_factory_relationships(): void
    {
        $mixedChart = MixedChart::factory()
            ->has(Post::factory())
            ->create();

        $this->assertInstanceOf(Post::class, $mixedChart->post);
    }

    /** @test */
    public function it_has_correct_table_name(): void
    {
        $this->assertEquals('mixed_charts', $this->mixedChart->getTable());
    }

    /** @test */
    public function it_has_correct_primary_key(): void
    {
        $this->assertEquals('id', $this->mixedChart->getKeyName());
    }

    /** @test */
    public function it_uses_incrementing_primary_key(): void
    {
        $this->assertTrue($this->mixedChart->getIncrementing());
    }

    /** @test */
    public function it_uses_timestamps(): void
    {
        $this->assertTrue($this->mixedChart->usesTimestamps());
    }

    /** @test */
    public function it_has_created_at_and_updated_at_columns(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'mixed_555',
            'post_type' => 'post',
        ]);

        $this->assertNotNull($mixedChart->created_at);
        $this->assertNotNull($mixedChart->updated_at);
    }

    /** @test */
    public function it_can_be_soft_deleted_if_enabled(): void
    {
        // Verifica se il modello supporta soft deletes
        if (method_exists($this->mixedChart, 'trashed')) {
            $mixedChart = MixedChart::create([
                'post_id' => 'mixed_666',
                'post_type' => 'post',
            ]);

            $mixedChart->delete();

            $this->assertSoftDeleted($mixedChart);
        } else {
            $this->markTestSkipped('Soft deletes not enabled for MixedChart model');
        }
    }

    /** @test */
    public function it_can_be_restored_if_soft_deletes_enabled(): void
    {
        // Verifica se il modello supporta soft deletes
        if (method_exists($this->mixedChart, 'trashed')) {
            $mixedChart = MixedChart::create([
                'post_id' => 'mixed_777',
                'post_type' => 'post',
            ]);

            $mixedChart->delete();
            $mixedChart->restore();

            $this->assertNotSoftDeleted($mixedChart);
        } else {
            $this->markTestSkipped('Soft deletes not enabled for MixedChart model');
        }
    }

    /** @test */
    public function it_can_be_permanently_deleted(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'mixed_888',
            'post_type' => 'post',
        ]);

        $mixedChartId = $mixedChart->id;
        $mixedChart->forceDelete();

        $this->assertDatabaseMissing('mixed_charts', ['id' => $mixedChartId]);
    }

    /** @test */
    public function it_can_be_found_by_id(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'mixed_999',
            'post_type' => 'post',
        ]);

        $foundMixedChart = MixedChart::find($mixedChart->id);

        $this->assertInstanceOf(MixedChart::class, $foundMixedChart);
        $this->assertEquals($mixedChart->id, $foundMixedChart->id);
    }

    /** @test */
    public function it_can_be_found_by_post_id(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'unique_mixed_post_123',
            'post_type' => 'post',
        ]);

        $foundMixedChart = MixedChart::where('post_id', 'unique_mixed_post_123')->first();

        $this->assertInstanceOf(MixedChart::class, $foundMixedChart);
        $this->assertEquals('unique_mixed_post_123', $foundMixedChart->post_id);
    }

    /** @test */
    public function it_can_be_found_by_post_type(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => '111',
            'post_type' => 'custom_mixed_type',
        ]);

        $foundMixedCharts = MixedChart::where('post_type', 'custom_mixed_type')->get();

        $this->assertCount(1, $foundMixedCharts);
        $this->assertEquals('custom_mixed_type', $foundMixedCharts->first()->post_type);
    }

    /** @test */
    public function it_can_be_found_by_chart_type(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => '222',
            'post_type' => 'post',
            'type' => 'mixed',
        ]);

        $foundMixedCharts = MixedChart::where('type', 'mixed')->get();

        $this->assertCount(1, $foundMixedCharts);
        $this->assertEquals('mixed', $foundMixedCharts->first()->type);
    }

    /** @test */
    public function it_can_be_filtered_by_multiple_criteria(): void
    {
        $mixedChart1 = MixedChart::create([
            'post_id' => '333',
            'post_type' => 'page',
            'type' => 'mixed',
            'width' => 800,
        ]);

        $mixedChart2 = MixedChart::create([
            'post_id' => '444',
            'post_type' => 'page',
            'type' => 'mixed',
            'width' => 1000,
        ]);

        $foundMixedCharts = MixedChart::where('post_type', 'page')
            ->where('type', 'mixed')
            ->where('width', '>=', 900)
            ->get();

        $this->assertCount(1, $foundMixedCharts);
        $this->assertEquals('444', $foundMixedCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_ordered_by_attributes(): void
    {
        $mixedChart1 = MixedChart::create([
            'post_id' => '555',
            'post_type' => 'post',
            'width' => 600,
        ]);

        $mixedChart2 = MixedChart::create([
            'post_id' => '666',
            'post_type' => 'post',
            'width' => 800,
        ]);

        $mixedChart3 = MixedChart::create([
            'post_id' => '777',
            'post_type' => 'post',
            'width' => 400,
        ]);

        $orderedMixedCharts = MixedChart::orderBy('width', 'asc')->get();

        $this->assertEquals(400, $orderedMixedCharts[0]->width);
        $this->assertEquals(600, $orderedMixedCharts[1]->width);
        $this->assertEquals(800, $orderedMixedCharts[2]->width);
    }

    /** @test */
    public function it_can_be_limited_in_results(): void
    {
        MixedChart::factory()->count(10)->create();

        $limitedMixedCharts = MixedChart::limit(5)->get();

        $this->assertCount(5, $limitedMixedCharts);
    }

    /** @test */
    public function it_can_be_paginated(): void
    {
        MixedChart::factory()->count(25)->create();

        $paginatedMixedCharts = MixedChart::paginate(10);

        $this->assertEquals(25, $paginatedMixedCharts->total());
        $this->assertEquals(10, $paginatedMixedCharts->perPage());
        $this->assertEquals(3, $paginatedMixedCharts->lastPage());
    }

    /** @test */
    public function it_can_be_counted(): void
    {
        MixedChart::factory()->count(7)->create();

        $count = MixedChart::count();

        $this->assertEquals(7, $count);
    }

    /** @test */
    public function it_can_be_summed_by_numeric_attribute(): void
    {
        MixedChart::create(['post_id' => '1', 'post_type' => 'post', 'width' => 100]);
        MixedChart::create(['post_id' => '2', 'post_type' => 'post', 'width' => 200]);
        MixedChart::create(['post_id' => '3', 'post_type' => 'post', 'width' => 300]);

        $totalWidth = MixedChart::sum('width');

        $this->assertEquals(600, $totalWidth);
    }

    /** @test */
    public function it_can_be_averaged_by_numeric_attribute(): void
    {
        MixedChart::create(['post_id' => '1', 'post_type' => 'post', 'width' => 100]);
        MixedChart::create(['post_id' => '2', 'post_type' => 'post', 'width' => 200]);
        MixedChart::create(['post_id' => '3', 'post_type' => 'post', 'width' => 300]);

        $averageWidth = MixedChart::avg('width');

        $this->assertEquals(200, $averageWidth);
    }

    /** @test */
    public function it_can_be_grouped_by_attribute(): void
    {
        MixedChart::create(['post_id' => '1', 'post_type' => 'post', 'type' => 'mixed']);
        MixedChart::create(['post_id' => '2', 'post_type' => 'post', 'type' => 'mixed']);
        MixedChart::create(['post_id' => '3', 'post_type' => 'page', 'type' => 'combo']);
        MixedChart::create(['post_id' => '4', 'post_type' => 'page', 'type' => 'combo']);

        $groupedMixedCharts = MixedChart::selectRaw('post_type, COUNT(*) as count')
            ->groupBy('post_type')
            ->get();

        $this->assertCount(2, $groupedMixedCharts);

        $postType = $groupedMixedCharts->where('post_type', 'post')->first();
        $pageType = $groupedMixedCharts->where('post_type', 'page')->first();

        $this->assertEquals(2, $postType->count);
        $this->assertEquals(2, $pageType->count);
    }

    /** @test */
    public function it_can_be_created_with_mass_assignment(): void
    {
        $mixedChartData = [
            'post_id' => 'mass_mixed_123',
            'post_type' => 'post',
            'type' => 'mixed',
            'width' => 900,
            'height' => 700,
        ];

        $mixedChart = MixedChart::create($mixedChartData);

        foreach ($mixedChartData as $key => $value) {
            $this->assertEquals($value, $mixedChart->$key);
        }
    }

    /** @test */
    public function it_can_be_updated_with_mass_assignment(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'mass_update_mixed_123',
            'post_type' => 'post',
            'type' => 'mixed',
        ]);

        $updateData = [
            'type' => 'combo',
            'width' => 1100,
            'height' => 800,
        ];

        $mixedChart->update($updateData);

        foreach ($updateData as $key => $value) {
            $this->assertEquals($value, $mixedChart->fresh()->$key);
        }
    }

    /** @test */
    public function it_can_be_filled_with_attributes(): void
    {
        $mixedChart = new MixedChart;

        $mixedChart->fill([
            'post_id' => 'fill_mixed_123',
            'post_type' => 'post',
            'type' => 'mixed',
        ]);

        $this->assertEquals('fill_mixed_123', $mixedChart->post_id);
        $this->assertEquals('post', $mixedChart->post_type);
        $this->assertEquals('mixed', $mixedChart->type);
    }

    /** @test */
    public function it_can_be_force_filled_with_attributes(): void
    {
        $mixedChart = new MixedChart;

        $mixedChart->forceFill([
            'post_id' => 'force_fill_mixed_123',
            'post_type' => 'post',
            'type' => 'combo',
        ]);

        $this->assertEquals('force_fill_mixed_123', $mixedChart->post_id);
        $this->assertEquals('post', $mixedChart->post_type);
        $this->assertEquals('combo', $mixedChart->type);
    }

    /** @test */
    public function it_can_be_duplicated(): void
    {
        $originalMixedChart = MixedChart::create([
            'post_id' => 'duplicate_mixed_123',
            'post_type' => 'post',
            'type' => 'mixed',
            'width' => 800,
            'height' => 600,
        ]);

        $duplicateMixedChart = $originalMixedChart->replicate();
        $duplicateMixedChart->post_id = 'duplicate_mixed_456';
        $duplicateMixedChart->save();

        $this->assertEquals($originalMixedChart->type, $duplicateMixedChart->type);
        $this->assertEquals($originalMixedChart->width, $duplicateMixedChart->width);
        $this->assertEquals($originalMixedChart->height, $duplicateMixedChart->height);
        $this->assertNotEquals($originalMixedChart->post_id, $duplicateMixedChart->post_id);
    }

    /** @test */
    public function it_can_be_refreshed_from_database(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'refresh_mixed_123',
            'post_type' => 'post',
            'type' => 'mixed',
        ]);

        // Modifica direttamente nel database
        MixedChart::where('id', $mixedChart->id)->update(['type' => 'combo']);

        $mixedChart->refresh();

        $this->assertEquals('combo', $mixedChart->type);
    }

    /** @test */
    public function it_can_be_touched_to_update_timestamps(): void
    {
        $mixedChart = MixedChart::create([
            'post_id' => 'touch_mixed_123',
            'post_type' => 'post',
        ]);

        $originalUpdatedAt = $mixedChart->updated_at;

        sleep(1); // Assicura che il timestamp sia diverso
        $mixedChart->touch();

        $this->assertNotEquals($originalUpdatedAt, $mixedChart->fresh()->updated_at);
    }

    /** @test */
    public function it_can_be_queried_with_where_in(): void
    {
        $mixedChart1 = MixedChart::create(['post_id' => 'where_in_mixed_1', 'post_type' => 'post']);
        $mixedChart2 = MixedChart::create(['post_id' => 'where_in_mixed_2', 'post_type' => 'post']);
        $mixedChart3 = MixedChart::create(['post_id' => 'where_in_mixed_3', 'post_type' => 'post']);

        $foundMixedCharts = MixedChart::whereIn('post_id', ['where_in_mixed_1', 'where_in_mixed_3'])->get();

        $this->assertCount(2, $foundMixedCharts);
        $this->assertTrue($foundMixedCharts->contains('post_id', 'where_in_mixed_1'));
        $this->assertTrue($foundMixedCharts->contains('post_id', 'where_in_mixed_3'));
    }

    /** @test */
    public function it_can_be_queried_with_where_between(): void
    {
        MixedChart::create(['post_id' => 'between_mixed_1', 'post_type' => 'post', 'width' => 100]);
        MixedChart::create(['post_id' => 'between_mixed_2', 'post_type' => 'post', 'width' => 500]);
        MixedChart::create(['post_id' => 'between_mixed_3', 'post_type' => 'post', 'width' => 1000]);

        $foundMixedCharts = MixedChart::whereBetween('width', [200, 800])->get();

        $this->assertCount(1, $foundMixedCharts);
        $this->assertEquals('between_mixed_2', $foundMixedCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_queried_with_where_null(): void
    {
        MixedChart::create(['post_id' => 'null_mixed_1', 'post_type' => 'post', 'width' => null]);
        MixedChart::create(['post_id' => 'null_mixed_2', 'post_type' => 'post', 'width' => 800]);

        $nullWidthMixedCharts = MixedChart::whereNull('width')->get();

        $this->assertCount(1, $nullWidthMixedCharts);
        $this->assertEquals('null_mixed_1', $nullWidthMixedCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_queried_with_where_not_null(): void
    {
        MixedChart::create(['post_id' => 'not_null_mixed_1', 'post_type' => 'post', 'width' => null]);
        MixedChart::create(['post_id' => 'not_null_mixed_2', 'post_type' => 'post', 'width' => 800]);

        $notNullWidthMixedCharts = MixedChart::whereNotNull('width')->get();

        $this->assertCount(1, $notNullWidthMixedCharts);
        $this->assertEquals('not_null_mixed_2', $notNullWidthMixedCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_queried_with_where_like(): void
    {
        MixedChart::create(['post_id' => 'like_mixed_123', 'post_type' => 'post']);
        MixedChart::create(['post_id' => 'like_mixed_456', 'post_type' => 'post']);
        MixedChart::create(['post_id' => 'other_mixed_789', 'post_type' => 'post']);

        $foundMixedCharts = MixedChart::where('post_id', 'like', '%like_mixed%')->get();

        $this->assertCount(2, $foundMixedCharts);
        $this->assertTrue($foundMixedCharts->contains('post_id', 'like_mixed_123'));
        $this->assertTrue($foundMixedCharts->contains('post_id', 'like_mixed_456'));
    }

    /** @test */
    public function it_can_be_queried_with_where_date(): void
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $mixedChart1 = MixedChart::create([
            'post_id' => 'date_mixed_1',
            'post_type' => 'post',
            'created_at' => $today,
        ]);

        $mixedChart2 = MixedChart::create([
            'post_id' => 'date_mixed_2',
            'post_type' => 'post',
            'created_at' => $yesterday,
        ]);

        $todayMixedCharts = MixedChart::whereDate('created_at', $today)->get();
        $yesterdayMixedCharts = MixedChart::whereDate('created_at', $yesterday)->get();

        $this->assertCount(1, $todayMixedCharts);
        $this->assertCount(1, $yesterdayMixedCharts);
        $this->assertEquals('date_mixed_1', $todayMixedCharts->first()->post_id);
        $this->assertEquals('date_mixed_2', $yesterdayMixedCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_queried_with_where_month(): void
    {
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;

        $mixedChart1 = MixedChart::create([
            'post_id' => 'month_mixed_1',
            'post_type' => 'post',
            'created_at' => now(),
        ]);

        $mixedChart2 = MixedChart::create([
            'post_id' => 'month_mixed_2',
            'post_type' => 'post',
            'created_at' => now()->subMonth(),
        ]);

        $currentMonthMixedCharts = MixedChart::whereMonth('created_at', $currentMonth)->get();
        $lastMonthMixedCharts = MixedChart::whereMonth('created_at', $lastMonth)->get();

        $this->assertCount(1, $currentMonthMixedCharts);
        $this->assertCount(1, $lastMonthMixedCharts);
    }

    /** @test */
    public function it_can_be_queried_with_where_year(): void
    {
        $currentYear = now()->year;
        $lastYear = now()->subYear()->year;

        $mixedChart1 = MixedChart::create([
            'post_id' => 'year_mixed_1',
            'post_type' => 'post',
            'created_at' => now(),
        ]);

        $mixedChart2 = MixedChart::create([
            'post_id' => 'year_mixed_2',
            'post_type' => 'post',
            'created_at' => now()->subYear(),
        ]);

        $currentYearMixedCharts = MixedChart::whereYear('created_at', $currentYear)->get();
        $lastYearMixedCharts = MixedChart::whereYear('created_at', $lastYear)->get();

        $this->assertCount(1, $currentYearMixedCharts);
        $this->assertCount(1, $lastYearMixedCharts);
    }

    /** @test */
    public function it_can_be_queried_with_where_time(): void
    {
        $currentTime = now()->format('H:i:s');
        $mixedChart = MixedChart::create([
            'post_id' => 'time_mixed_1',
            'post_type' => 'post',
            'created_at' => now(),
        ]);

        $foundMixedCharts = MixedChart::whereTime('created_at', $currentTime)->get();

        $this->assertCount(1, $foundMixedCharts);
        $this->assertEquals('time_mixed_1', $foundMixedCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_queried_with_where_day(): void
    {
        $currentDay = now()->day;
        $mixedChart = MixedChart::create([
            'post_id' => 'day_mixed_1',
            'post_type' => 'post',
            'created_at' => now(),
        ]);

        $foundMixedCharts = MixedChart::whereDay('created_at', $currentDay)->get();

        $this->assertCount(1, $foundMixedCharts);
        $this->assertEquals('day_mixed_1', $foundMixedCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_queried_with_where_hour(): void
    {
        $currentHour = now()->hour;
        $mixedChart = MixedChart::create([
            'post_id' => 'hour_mixed_1',
            'post_type' => 'post',
            'created_at' => now(),
        ]);

        $foundMixedCharts = MixedChart::whereHour('created_at', $currentHour)->get();

        $this->assertCount(1, $foundMixedCharts);
        $this->assertEquals('hour_mixed_1', $foundMixedCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_queried_with_where_minute(): void
    {
        $currentMinute = now()->minute;
        $mixedChart = MixedChart::create([
            'post_id' => 'minute_mixed_1',
            'post_type' => 'post',
            'created_at' => now(),
        ]);

        $foundMixedCharts = MixedChart::whereMinute('created_at', $currentMinute)->get();

        $this->assertCount(1, $foundMixedCharts);
        $this->assertEquals('minute_mixed_1', $foundMixedCharts->first()->post_id);
    }

    /** @test */
    public function it_can_be_queried_with_where_second(): void
    {
        $currentSecond = now()->second;
        $mixedChart = MixedChart::create([
            'post_id' => 'second_mixed_1',
            'post_type' => 'post',
            'created_at' => now(),
        ]);

        $foundMixedCharts = MixedChart::whereSecond('created_at', $currentSecond)->get();

        $this->assertCount(1, $foundMixedCharts);
        $this->assertEquals('second_mixed_1', $foundMixedCharts->first()->post_id);
    }
}
