<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Migrazione per la creazione della tabella delle visualizzazioni degli articoli.
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
                $table->string('ip_address', 55);
                $table->string('user_agent', 255);
                $table->foreignId('post_id')
                    ->constrained('blog_articles')
                    ->cascadeOnDelete();
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
                $table->string('session_id')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamp('viewed_at');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['post_id', 'ip_address', 'user_agent']);
                $table->index(['post_id', 'user_id']);
                $table->index('viewed_at');
            }
        );
    }
};
