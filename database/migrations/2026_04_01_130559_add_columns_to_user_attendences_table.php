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
        Schema::table('user_attendences', function (Blueprint $table) {
            $table->string('in_ip', 50)->nullable()->after('hours');
            $table->string('in_lat', 50)->nullable()->after('in_ip');
            $table->string('in_lng', 50)->nullable()->after('in_lat');

            $table->string('out_ip', 50)->nullable()->after('in_lng');
            $table->string('out_lat', 50)->nullable()->after('out_ip');
            $table->string('out_lng', 50)->nullable()->after('out_lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_attendences', function (Blueprint $table) {
            $table->dropColumn('in_ip');
            $table->dropColumn('in_lat');
            $table->dropColumn('in_lng');
            $table->dropColumn('out_ip');
            $table->dropColumn('out_lat');
            $table->dropColumn('out_lng');
        });
    }
};
