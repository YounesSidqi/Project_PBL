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
        Schema::create('tugas_f2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_magang_id')->constrained('pengajuan_magangs')->cascadeOnDelete();
            $table->string('modul_pekerjaan');
            $table->text('spesifikasi_kebutuhan');
            $table->string('target_minggu')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'acc'])->default('todo');
            $table->text('catatan_industri')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_f2');
    }
};
