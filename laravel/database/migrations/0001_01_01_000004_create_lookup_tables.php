<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('directors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->notNull();
        });

        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->notNull();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->notNull();
        });

        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->notNull();
        });

        Schema::create('halls', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->notNull();
        });

        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->nullable();
        });

        Schema::create('souvenir_status', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->nullable();
        });

        Schema::create('franchises', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->notNull();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchises');
        Schema::dropIfExists('souvenir_status');
        Schema::dropIfExists('category');
        Schema::dropIfExists('halls');
        Schema::dropIfExists('genres');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('studios');
        Schema::dropIfExists('directors');
    }
};
