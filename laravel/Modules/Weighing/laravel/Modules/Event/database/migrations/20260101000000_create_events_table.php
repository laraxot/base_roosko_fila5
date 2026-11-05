<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create the events table.
 *
 * This migration creates the Events table with columns for storing event data.
 * It includes:
 * - id (primary key)
 * - uuid (unique identifier)
 * - user_id (foreign key to User)
 * - title (event title)
 * - slug (URL-friendly identifier)
 * - description (brief description)
 * - description_long (longer description)
 * - image (image URL)
 * - category (foreign key to Categories)
 * - event_start_date (event start datetime)
 * - event_end_date (event end datetime)
 * - type (event type enum)
 * - is_wagerable (boolean)
 * - bet_end_date (optional betting deadline)
 * - is_featured (boolean)
 * - show_on_homepage (boolean)
 * - main_image_upload (boolean)
 * - main_image_url (image URL)
 * - published_at (publication timestamp)
 * - closed_at (closure timestamp)
 * - category_id (foreign key to Categories)
 * - content_type (type of content)
 * - type (event type)
 */
class CreateEventsTable extends Migration
{
    public function up(): void
    {
        // Create the events table
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            // `after()` non esiste su ForeignKeyDefinition: colonna già in ordine di create.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description');
            $table->text('description_long');
            $table->string('image');
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->onDelete('set null');
            $table->dateTime('event_start_date');
            $table->dateTime('event_end_date');
            $table->boolean('is_wagerable');
            $table->dateTime('bet_end_date');
            $table->boolean('is_featured');
            $table->boolean('show_on_homepage');
            $table->string('main_image_upload');
            $table->string('main_image_url');
            $table->dateTime('published_at');
            $table->dateTime('closed_at');
            $table->string('content_type');
            $table->string('type');
            $table->boolean('type_is_wagerable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
}
