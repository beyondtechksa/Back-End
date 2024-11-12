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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->bigInteger('size')->nullable();
            $table->string('type')->nullable();
            $table->string('extension')->nullable();
            $table->unsignedbigInteger('media_category_id')->nullable();
            $table->foreign('media_category_id')->references('id')->on('media_categories')->onDelete('cascade');
            $table->string('media_type')->default('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
