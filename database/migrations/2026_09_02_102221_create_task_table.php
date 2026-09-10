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
        Schema::create('task', function (Blueprint $table) {
            $table->id();
             // Task information
            $table->string('name');
            $table->string('status');

            // Users
            $table->foreignId('assigner_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('assignee_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Scheduling
            $table->boolean('is_recurring')
                ->default(false);

            $table->boolean('is_auto_scheduled')
                ->default(false);

            $table->dateTime('start_at')
                ->nullable();

            $table->dateTime('end_at')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
};
