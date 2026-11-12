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
        Schema::table('pengajuan_magangs', function (Blueprint $table) {
            $table->string('nama_perusahaan_surat')->nullable()->after('dosen_id');
            $table->text('alamat_perusahaan_surat')->nullable()->after('nama_perusahaan_surat');
            $table->enum('status_penempatan', ['pending', 'acc', 'reject'])->nullable()->after('status_surat_pengantar');
            $table->string('file_surat_keterangan')->nullable()->after('file_surat_pengantar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_magangs', function (Blueprint $table) {
            $table->dropColumn([
                'nama_perusahaan_surat',
                'alamat_perusahaan_surat',
                'status_penempatan',
                'file_surat_keterangan',
            ]);
        });
    }
};
