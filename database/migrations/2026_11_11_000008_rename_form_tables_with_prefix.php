<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('f1_applications')) {
            Schema::rename('f1_applications', 'form_f1_pengajuan');
        }

        if (Schema::hasTable('tugas_f2')) {
            Schema::rename('tugas_f2', 'form_f2_tugas');
        }

        if (Schema::hasTable('logbooks')) {
            Schema::rename('logbooks', 'form_f3_logbook');
        }

        if (Schema::hasTable('bimbingan_f4')) {
            Schema::rename('bimbingan_f4', 'form_f4_bimbingan');
        }

        if (Schema::hasTable('evaluasi_f5')) {
            Schema::rename('evaluasi_f5', 'form_f5_evaluasi');
        }

        if (Schema::hasTable('evaluasi_f6')) {
            Schema::rename('evaluasi_f6', 'form_f6_evaluasi');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('form_f1_pengajuan')) {
            Schema::rename('form_f1_pengajuan', 'f1_applications');
        }

        if (Schema::hasTable('form_f2_tugas')) {
            Schema::rename('form_f2_tugas', 'tugas_f2');
        }

        if (Schema::hasTable('form_f3_logbook')) {
            Schema::rename('form_f3_logbook', 'logbooks');
        }

        if (Schema::hasTable('form_f4_bimbingan')) {
            Schema::rename('form_f4_bimbingan', 'bimbingan_f4');
        }

        if (Schema::hasTable('form_f5_evaluasi')) {
            Schema::rename('form_f5_evaluasi', 'evaluasi_f5');
        }

        if (Schema::hasTable('form_f6_evaluasi')) {
            Schema::rename('form_f6_evaluasi', 'evaluasi_f6');
        }
    }
};
