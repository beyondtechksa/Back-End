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
        Schema::create('traking_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('trendyol_product_id')->nullable();
            $table->string('sku')->nullable();
            $table->string('name')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('desc')->nullable();
            $table->string('desc_ar')->nullable();
            $table->decimal('discount_price')->nullable();
            $table->decimal('discount_percentage')->nullable();
            $table->decimal('final_price')->nullable();
            $table->decimal('price')->nullable();
            $table->string('link')->nullable();
            $table->text('images')->nullable();
            $table->text('attributes')->nullable();
            $table->string('group_link')->nullable();
            $table->string('company_name')->nullable();
            $table->integer('stock_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traking_products');
    }
};
