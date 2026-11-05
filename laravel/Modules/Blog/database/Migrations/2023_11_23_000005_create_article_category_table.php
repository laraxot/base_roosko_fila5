<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Migrazione per la creazione della tabella pivot tra articoli e categorie.
 */
return new class extends XotBaseMigration {
    /**
     * Esegue la migrazione del database.
     */
    public function up(): void
    {
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->foreignId('category_id')
                    ->constrained('blog_categories')
                    ->cascadeOnDelete();
                $table->foreignId('article_id')
                    ->constrained('blog_articles')
                    ->cascadeOnDelete();
                $table->integer('order')->default(0);
                $table->boolean('is_primary')->default(false);
                $table->timestamps();

                $table->unique(['category_id', 'article_id']);
                $table->index(['category_id', 'order']);
            }
        );
    }
};
