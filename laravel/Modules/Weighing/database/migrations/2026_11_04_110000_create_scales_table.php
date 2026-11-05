<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Weighing\Models\Scale;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = Scale::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('code', 64)->unique();
            $table->string('name');
            $table->string('type', 32);
            $table->string('driver', 32);
            $table->string('host')->nullable();
            $table->unsignedInteger('port')->nullable();
            $table->decimal('max_capacity_kg', 14, 3);
            $table->decimal('resolution_kg', 14, 3);
            $table->date('verified_until')->nullable();
            $table->boolean('is_active')->default(true);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, true);
        });
    }
};
