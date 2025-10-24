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
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama');
            $table->string('email')->unique();
            // $table->string('password');
            $table->text('alamat')->nullable();
            $table->string('telepon', 15)->nullable();
            $table->enum('status_keanggotaan', ['Aktif', 'Non-Aktif', 'Diblokir'])->default('Aktif');
            $table->string('foto_profil')->nullable();
            // $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
