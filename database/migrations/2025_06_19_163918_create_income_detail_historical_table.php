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
        Schema::create('income_detail_historical', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('income_id');
            $table->bigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('purchase_price',11,2);
            $table->decimal('sale_price',11,2);
            $table->string('form_sale', 50)->nullable();
            $table->date('expiration_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_detail_historical');
       
    }
};
