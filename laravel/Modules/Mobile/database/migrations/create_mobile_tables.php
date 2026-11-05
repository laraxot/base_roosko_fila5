<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('mobile_waiter_sessions')) {
            Schema::create('mobile_waiter_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->index();
            $table->string('device_id')->index();
            $table->string('device_name');
            $table->string('platform')->comment('ios|android');
            $table->string('token', 255)->nullable();
            $table->boolean('biometric_enabled')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('location_lat', 10, 7)->nullable();
            $table->decimal('location_lng', 10, 7)->nullable();
            $table->foreignId('shift_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['device_id']);
            });
        }

        if (! Schema::hasTable('mobile_order_queue')) {
            Schema::create('mobile_order_queue', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('waiter_session_id')->index();
            $table->foreignUuid('table_id')->index();
            $table->text('order_data');
            $table->string('status', 20)->default('pending');
            $table->unsignedSmallInteger('sync_attempts')->default(0);
            $table->timestamp('last_sync_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            });
        }

        if (! Schema::hasTable('mobile_notifications')) {
            Schema::create('mobile_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('waiter_session_id')->index();
            $table->string('type', 50);
            $table->text('title');
            $table->text('body');
            $table->json('data')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['waiter_session_id', 'read']);
            });
        }

        if (! Schema::hasTable('mobile_push_tokens')) {
            Schema::create('mobile_push_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('waiter_session_id')->index();
            $table->string('device_token', 255);
            $table->string('platform', 10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['device_token', 'platform']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_waiter_sessions');
        Schema::dropIfExists('mobile_order_queue');
        Schema::dropIfExists('mobile_notifications');
        Schema::dropIfExists('mobile_push_tokens');
    }
};
