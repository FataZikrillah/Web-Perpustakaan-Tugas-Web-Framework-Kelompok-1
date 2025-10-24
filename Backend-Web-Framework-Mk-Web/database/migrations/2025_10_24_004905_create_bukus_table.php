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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('cover_buku')->nullable();
            $table->string('isbn', 20)->unique()->nullable();
            $table->integer('tahun_terbit')->nullable();
            $table->integer('stok')->default(0);
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->onDelete('set null');
            $table->foreignId('penerbit_id')->nullable()->constrained('penerbits')->onDelete('set null');
            $table->timestamps();

            // untuk mempercepat pencarian berdasarkan judul dan isbn
            $table->index(['judul', 'isbn']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
