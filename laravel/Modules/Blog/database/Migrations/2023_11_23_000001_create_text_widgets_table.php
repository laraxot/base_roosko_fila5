<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Migrazione per la creazione della tabella dei widget testuali.
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
                $table->string('key')->unique();
                $table->string('title', 2048)->nullable();
                $table->longText('content')->nullable();
                $table->string('image', 2048)->nullable();
                $table->string('position')->nullable();
                $table->integer('order')->default(0);
                $table->json('data')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }
};
