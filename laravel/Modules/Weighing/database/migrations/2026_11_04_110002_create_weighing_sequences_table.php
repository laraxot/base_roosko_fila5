<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Weighing\Models\WeighingSequence;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Contatore annuale dei ticket (W-{anno}-{progr}), bloccato con lockForUpdate da
 * GenerateWeighingTicketNumberAction. L'unique su `year` rende sicuro l'insertOrIgnore concorrente.
 */
return new class extends XotBaseMigration
{
    protected ?string $model_class = WeighingSequence::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('year')->unique();
            $table->unsignedInteger('last_number')->default(0);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, false);
        });
    }
};
