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
        Schema::table('logbooks', function (Blueprint $table) {
            $table->foreignId('pengajuan_magang_id')->nullable()->after('dosen_id')->constrained('pengajuan_magangs')->nullOnDelete();
            $table->integer('hari_terlambat')->default(0)->after('tanggal');
            $table->text('catatan_industri')->nullable()->after('feedback');
            $table->string('status_industri', 30)->default('pending')->after('status'); // pending, acc, revisi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_magang_id']);
            $table->dropColumn(['pengajuan_magang_id', 'hari_terlambat', 'catatan_industri', 'status_industri']);
        });
    }
};
