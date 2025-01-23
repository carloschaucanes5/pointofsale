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
        Schema::table('sale_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('sale_id');
            // Crear la llave foránea
            $table->foreign('sale_id')->references('id')->on('sale')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_detail', function (Blueprint $table) {
            //
        });
    }
};
