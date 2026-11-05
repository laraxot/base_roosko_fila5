<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    /**
     * Esegue la migrazione del database.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->uuid('uuid')->nullable();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('content')->nullable();
                $table->text('excerpt')->nullable();
                $table->text('description');
                $table->text('main_image_url')->nullable();
                $table->text('main_image_upload')->nullable();
                $table->json('content_blocks');
                $table->json('sidebar_blocks');
                $table->json('footer_blocks');

                $table->foreignId('category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
                $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();

                $table->string('status')->nullable();
                $table->boolean('status_display')->default(false);
                $table->boolean('show_on_homepage')->default(false);
                $table->boolean('is_featured')->default(false);
                $table->integer('read_time')->nullable();

                // Date e timestamp
                $table->timestamp('published_at')->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->timestamp('rewarded_at')->nullable();

                // Campi per scommesse e punteggi
                $table->boolean('is_wagerable')->default(false);
                $table->boolean('wagers')->nullable();
                $table->integer('wagers_count')->nullable();
                $table->integer('wagers_count_canonical')->nullable();
                $table->integer('wagers_count_total')->nullable();
                $table->timestamp('bet_end_date')->nullable();
                $table->timestamp('event_start_date')->nullable();
                $table->timestamp('event_end_date')->nullable();

                // Punteggi e volumi
                $table->string('brier_score')->nullable();
                $table->string('brier_score_play_money')->nullable();
                $table->string('brier_score_real_money')->nullable();
                $table->float('volume_play_money')->nullable();
                $table->float('volume_real_money')->nullable();

                // Altri campi
                $table->string('type')->nullable();
                $table->boolean('is_following')->default(false);

                // Timestamps standard
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }
};
