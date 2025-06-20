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
        Schema::table('income_detail_historical', function (Blueprint $table) {
            $table->bigInteger("income_detail_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income_detail_historical', function (Blueprint $table) {
            $table->dropColumn('income_detail_id');
        });
    }
};
