<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\Models\Category;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Migrazione per la creazione della tabella delle categorie del blog.
 */
return new class extends XotBaseMigration {
    protected ?string $model_class = Category::class;

    /**
     * Esegue la migrazione del database.
     */
    public function up(): void
    {
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->text('icon')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->foreign('parent_id')
                    ->references('id')
                    ->on('blog_categories')
                    ->nullOnDelete();
                $table->integer('order')->default(0);
                $table->boolean('is_visible')->default(true);
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }
};
