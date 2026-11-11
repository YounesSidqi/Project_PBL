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
        Schema::create('pengajuan_magangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('industri_id')->nullable()->constrained('industris')->nullOnDelete();
            $table->foreignId('dosen_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('judul_magang')->nullable();
            $table->string('posisi_magang')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status_surat_pengantar', ['draft', 'pending', 'diterbitkan', 'ditolak'])->default('draft');
            $table->enum('status_dosen', ['pending', 'usulan', 'acc_kps'])->default('pending');
            $table->string('nomor_surat_pengantar')->nullable();
            $table->string('file_surat_pengantar')->nullable();
            $table->string('file_f1')->nullable();
            $table->string('file_f7')->nullable();
            $table->string('nilai_akhir_fuzzy')->nullable(); // e.g. "41.9/50"
            $table->float('fuzzy_score_num')->nullable();
            $table->float('fuzzy_percentage')->nullable();
            $table->text('catatan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_magangs');
    }
};
