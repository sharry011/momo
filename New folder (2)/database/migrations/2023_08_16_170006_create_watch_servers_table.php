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
        Schema::create('watch_servers', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('img')->nullable();
            $table->integer('rank')->nullable();
            $table->text('remove')->nullable();
            $table->text('add')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_servers');
    }
};
