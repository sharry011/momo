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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 256)->nullable();
            $table->string('slug', 256)->unique();
            $table->text('img')->nullable();
            $table->text('story')->nullable();
            $table->integer('views')->nullable();
            $table->decimal('rating', 5, 1)->nullable();
            $table->integer('opt');
            $table->string('num')->nullable();
            $table->integer('season_id')->nullable();
            $table->string('runtime', 10)->nullable();
            $table->text('triller')->nullable();
            $table->integer('year')->nullable();
            $table->text('watch_servers')->nullable();
            $table->text('down_servers')->nullable();
            $table->integer('show')->default(1);
            $table->integer('pin_index')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
