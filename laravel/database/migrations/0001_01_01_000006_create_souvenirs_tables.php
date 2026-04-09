<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('souvenirs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->notNull();
            $table->decimal('price', 10, 2)->notNull();
            $table->foreignId('category_id')->nullable()->constrained('category')->nullOnDelete();
            $table->foreignId('movie_id')->nullable()->constrained('movies')->nullOnDelete();
            $table->integer('quantity')->default(0);
            $table->foreignId('status_id')->nullable()->constrained('souvenir_status')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('souvenir_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('souvenir_id')->constrained('souvenirs')->cascadeOnDelete();
            $table->text('url')->notNull();
            $table->boolean('is_primary')->default(false);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('souvenir_images');
        Schema::dropIfExists('souvenirs');
    }
};
