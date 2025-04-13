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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('admin_link', 45);
            $table->integer('per_page');
            $table->text('site_name')->nullable();
            $table->text('site_desc')->nullable();
            $table->text('keywords')->nullable();
            $table->text('site_logo')->nullable();
            $table->text('site_footer')->nullable();
            $table->integer('icon_index')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
