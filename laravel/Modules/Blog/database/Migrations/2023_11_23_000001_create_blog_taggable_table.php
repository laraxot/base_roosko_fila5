<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\Models\Taggable;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Migrazione per la creazione della tabella pivot dei tag del blog.
 */
return new class extends XotBaseMigration {
    protected ?string $model_class = Taggable::class;

    /**
     * Esegue la migrazione del database.
     */
    public function up(): void
    {
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->foreignId('tag_id')
                    ->constrained('blog_tags')
                    ->cascadeOnDelete();
                $table->morphs('taggable');
                $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
                $table->timestamps();
            }
        );
    }
};
