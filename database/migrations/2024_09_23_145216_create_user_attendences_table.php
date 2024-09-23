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
        Schema::create('user_attendences', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('in_time', 25)->nullable();
            $table->string('out_time', 25)->nullable();
            $table->string('in_status', 20)->nullable();
            $table->string('out_status', 20)->nullable();
            $table->decimal('hours', 18, 4)->default(0.0000);
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: Absent, 1: Present, 2: Public Holiday, 3: Leave, 4: Rest Day, 5: Markout Missing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_attendences');
    }
};
