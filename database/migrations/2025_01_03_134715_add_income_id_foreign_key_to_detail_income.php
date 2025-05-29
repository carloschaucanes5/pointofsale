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
        Schema::table('income_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('income_id');

            // Crear la llave foránea
            $table->foreign('income_id')->references('id')->on('income')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income_detail', function (Blueprint $table) {
            //
        });
    }
};
