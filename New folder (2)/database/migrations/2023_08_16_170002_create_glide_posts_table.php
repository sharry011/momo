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
        Schema::create('glide_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_post', false, true);

            $table->foreign('id_post')->references('id')->on('posts')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glide_posts');
    }
};
