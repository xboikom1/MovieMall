<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->notNull();
            $table->text('description')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->date('release_date')->nullable();
            $table->integer('length_minutes')->nullable();
            $table->foreignId('director_id')->nullable()->constrained('directors')->nullOnDelete();
            $table->foreignId('studio_id')->nullable()->constrained('studios')->nullOnDelete();
            $table->foreignId('language_id')->nullable()->constrained('languages')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('movie_genres', function (Blueprint $table) {
            $table->foreignId('genre_id')->constrained('genres')->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained('movies')->cascadeOnDelete();
            $table->primary(['genre_id', 'movie_id']);
        });

        Schema::create('movie_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->cascadeOnDelete();
            $table->text('url')->notNull();
            $table->boolean('is_primary')->default(false);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained('halls')->cascadeOnDelete();
            $table->string('row_label', 5)->notNull();
            $table->integer('seat_number')->notNull();
        });

        Schema::create('schedule_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->cascadeOnDelete();
            $table->foreignId('hall_id')->constrained('halls')->cascadeOnDelete();
            $table->timestamp('starts_at')->notNull();
            $table->timestamp('ends_at')->notNull();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_id')->constrained('seats')->cascadeOnDelete();
            $table->foreignId('schedule_slot_id')->constrained('schedule_slots')->cascadeOnDelete();
            $table->decimal('price', 10, 2)->notNull();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('schedule_slots');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('movie_images');
        Schema::dropIfExists('movie_genres');
        Schema::dropIfExists('movies');
    }
};
