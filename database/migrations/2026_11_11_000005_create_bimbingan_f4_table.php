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
        Schema::create('bimbingan_f4', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_magang_id')->constrained('pengajuan_magangs')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->integer('sesi_ke');
            $table->date('tanggal_bimbingan');
            $table->text('materi_bimbingan');
            $table->text('progres_laporan')->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->enum('status', ['pending', 'acc'])->default('acc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingan_f4');
    }
};
