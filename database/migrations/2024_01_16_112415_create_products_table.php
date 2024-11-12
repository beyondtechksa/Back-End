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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('name_tr');
            $table->string('desc_en');
            $table->string('desc_ar');
            $table->string('desc_tr');
            $table->string('sku');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            //$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('collection_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->unsignedBigInteger('vendor_id')->nullable();
           // $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade'); // Assuming you have a vendors table
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('discount_percentage_selling_price', 10, 2)->nullable();
            $table->decimal('packaging_shipping_fees', 10, 2)->nullable();
            $table->decimal('management_fees', 10, 2)->nullable();
            $table->decimal('profit_percentage', 10, 2)->nullable();
            $table->decimal('tax_percentage', 10, 2)->nullable();
            $table->decimal('commercial_percentage', 10, 2)->nullable();
            $table->decimal('manual_price_adjustment', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
            $table->decimal('final_selling_price', 10, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('shipping')->default(1);
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->string('dimension_unit')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->string('weight_unit')->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->decimal('discount_percentage', 10, 2)->nullable(); // Added this column

            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->integer('points')->nullable();
            $table->string('image');
            $table->string('source_link')->nullable();
            $table->integer('status')->default(0);
            $table->integer('featured')->default(0);
            $table->integer('new_arrival')->default(0);
            $table->integer('ontop')->default(0);
            $table->integer('home')->default(0);
            $table->integer('trending')->default(0);
            $table->integer('highlighted')->default(0);
            $table->integer('most_sold')->default(0);
            $table->json('attributes_ids')->nullable();
            $table->json('colors_ids')->nullable();
            $table->json('sizes_ids')->nullable();
            $table->json('scraped_attributes')->nullable();
            $table->string('model_number')->nullable();
            $table->string('group_link')->nullable();
            $table->string('stock_status')->nullable();
            $table->json('related')->nullable(); // Added this column
            $table->integer('home_forniture')->default(0); // Added this column

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
