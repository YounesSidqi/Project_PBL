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
        Schema::create('evaluasi_f5', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_magang_id')->unique()->constrained('pengajuan_magangs')->cascadeOnDelete();
            // Softskills (Skala 0 - 100)
            $table->float('skor_komunikasi');
            $table->float('skor_berpikir_kritis');
            $table->float('skor_kerjasama');
            $table->float('skor_inisiatif');
            $table->float('skor_literasi');
            // Hardskills (Skala 0 - 100)
            $table->float('skor_deskripsi_produk');
            $table->float('skor_spesifikasi');
            $table->float('skor_desain');
            $table->float('skor_implementasi');
            $table->float('skor_pengujian');
            // Ringkasan
            $table->float('rata_rata_f5');
            $table->text('saran_industri')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_f5');
    }
};
