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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('note')->nullable();
            $table->string('flag');
            $table->string('prefix');
            $table->float('exchange_rate');
            $table->decimal('shipping_fees',10,2)->nullable();
            $table->decimal('free_shipping_amount',10,2)->nullable();
            $table->integer('country_tax')->nullable();  // %
            $table->string('country_tax_prefix')->nullable();
            $table->integer('main')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
