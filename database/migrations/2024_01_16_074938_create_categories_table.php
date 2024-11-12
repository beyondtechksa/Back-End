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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('list')->default(0);
            $table->text('name');
            $table->text('menu_name');
            $table->text('slug')->unique(); // Fixed typo here
            $table->text('desc')->nullable();
            $table->string('image')->nullable();
            $table->integer('status')->nullable();
            $table->boolean('show_in_navbar')->default(false); // Added show_in_navbar field
            $table->boolean('mark_as_top_category')->default(false); // Added mark_as_top_category field
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
