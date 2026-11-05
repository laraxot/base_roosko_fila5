<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\Models\Tag;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Migrazione per la creazione della tabella dei tag del blog.
 */
return new class extends XotBaseMigration {
    protected ?string $model_class = Tag::class;

    /**
     * Esegue la migrazione del database.
     */
    public function up(): void
    {
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->json('name');
                $table->json('slug');
                $table->string('type')->nullable();
                $table->text('description')->nullable();
                $table->string('color')->nullable();
                $table->integer('order_column')->nullable();
                $table->boolean('is_visible')->default(true);
                $table->integer('articles_count')->default(0);
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }
};
