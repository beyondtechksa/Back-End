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
        Schema::create('price_formulas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('note')->nullable();
            $table->decimal('discount_percentage_selling_price',10,2)->nullable();
            $table->decimal('packaging_shipping_fees',10,2)->nullable();
            $table->decimal('management_fees',10,2)->nullable();
            $table->decimal('profit_percentage',10,2)->nullable();
            $table->decimal('commercial_percentage',10,2)->nullable();
            $table->decimal('manual_price_adjustment',10,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_formulas');
    }
};
