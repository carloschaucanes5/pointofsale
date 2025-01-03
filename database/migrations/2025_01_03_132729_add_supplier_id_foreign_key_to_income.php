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
        Schema::table('income', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id');

            // Crear la llave foránea
            $table->foreign('supplier_id')->references('id')->on('person')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income', function (Blueprint $table) {
            //
        });
    }
};
