<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->string('slug', 256)->unique();
            $table->text('img')->nullable();
            $table->text('story')->nullable();
            $table->decimal('rating', 5, 1)->nullable();
            $table->integer('num')->nullable();
            $table->integer('serie_id')->nullable();
            $table->text('triller')->nullable();
            $table->integer('year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
