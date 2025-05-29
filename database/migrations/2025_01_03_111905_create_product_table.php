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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('code',50);
            $table->string('name',100);
            $table->string('concentration',30);
            $table->string('presentation',50);
            $table->integer('stock');
            $table->string('description',512);
            $table->string('image',50);
            $table->integer('status');
            $table->string('laboratory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
