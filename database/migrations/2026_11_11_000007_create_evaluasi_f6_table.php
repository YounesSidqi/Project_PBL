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
        Schema::create('evaluasi_f6', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_magang_id')->unique()->constrained('pengajuan_magangs')->cascadeOnDelete();
            // 9 Indikator Akademik (Skala 0 - 100)
            $table->float('skor_kesesuaian_judul');
            $table->float('skor_tata_bahasa');
            $table->float('skor_format_laporan');
            $table->float('skor_pengetahuan');
            $table->float('skor_tanya_jawab');
            $table->float('skor_etika');
            $table->float('skor_justifikasi');
            $table->float('skor_rutinitas_bimbingan');
            $table->float('skor_progres');
            // Ringkasan
            $table->float('rata_rata_f6');
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_f6');
    }
};
