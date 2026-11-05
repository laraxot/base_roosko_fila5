<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Weighing\Models\Weighing;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * `weighable_*` e' una FK cross-modulo (conferimento, uscita...): colonne indicizzate senza vincolo.
 * `scale_id` e' interna al modulo: solo indice, come da convenzione per i modelli su connessione modulo.
 */
return new class extends XotBaseMigration
{
    protected ?string $model_class = Weighing::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('ticket_number', 32)->unique();
            $table->nullableMorphs('weighable');
            $table->unsignedBigInteger('scale_id')->nullable()->index();
            $table->string('direction', 16)->index();
            $table->string('status', 16)->index();
            $table->string('vehicle_plate', 20)->nullable()->index();
            $table->string('trailer_plate', 20)->nullable();
            $table->decimal('gross_kg', 14, 3)->nullable();
            $table->decimal('tare_kg', 14, 3)->nullable();
            $table->decimal('net_kg', 14, 3)->nullable();
            $table->timestamp('gross_weighed_at')->nullable();
            $table->timestamp('tare_weighed_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->text('void_reason')->nullable();
            $table->json('raw_readings')->nullable();
            $table->text('notes')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('voided_at')) {
                $table->timestamp('voided_at')->nullable();
            }
            if (! $this->hasColumn('void_reason')) {
                $table->text('void_reason')->nullable();
            }

            $this->updateTimestamps($table, false);
        });
    }
};
