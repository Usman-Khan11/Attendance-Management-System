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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('username', 255);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('image', 255)->nullable();
            $table->text('access')->nullable();
            $table->string('password', 255);
            $table->timestamps();
        });

        DB::table('admins')->insert([
            'name' => 'Master Admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'email_verified_at' => now(),
            'image' => null,
            'access' => null,
            'password' => bcrypt('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
