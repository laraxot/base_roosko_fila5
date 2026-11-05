<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\Models\Status;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Migrazione per la creazione della tabella degli stati del blog.
 */
return new class extends XotBaseMigration {
    protected ?string $model_class = Status::class;

    /**
     * Esegue la migrazione del database.
     */
    public function up(): void
    {
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('color')->nullable();
                $table->text('reason')->nullable();
                $table->text('description')->nullable();
                $table->morphs('model');
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }
};
