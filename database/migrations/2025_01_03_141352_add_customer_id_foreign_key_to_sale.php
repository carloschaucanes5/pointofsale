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
        Schema::table('sale', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id');

            // Crear la llave foránea
            $table->foreign('customer_id')->references('id')->on('person')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale', function (Blueprint $table) {
            //
        });
    }
};
