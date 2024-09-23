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
        Schema::create('user_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('in_time', 20);
            $table->string('out_time', 20);
            $table->string('join_date', 20)->nullable();
            $table->string('resign_date', 20)->nullable();
            $table->decimal('hours', 18, 4)->default(0.0000);
            $table->text('restday')->nullable();
            $table->string('annual_leave', 10)->default('22')->nullable();
            $table->string('emergency_leave', 10)->default('6')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_schedules');
    }
};
