<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('sitename', 100)->nullable();
            $table->string('primary_color', 20)->nullable();
            $table->integer('page_length')->default(10);
            $table->integer('annual_leave')->default(0);
            $table->integer('emergency_leave')->default(0);
            $table->timestamps();
        });

        DB::table('general_settings')->insert([
            'sitename' => 'Attendance Management System',
            'primary_color' => '#04487F',
            'page_length' => 15,
            'annual_leave' => 22,
            'emergency_leave' => 6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
