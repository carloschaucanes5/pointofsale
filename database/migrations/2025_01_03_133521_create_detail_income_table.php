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
        Schema::create('income_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('purchase_price',11,2);
            $table->decimal('sale_price',11,2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_detail');
    }
};
