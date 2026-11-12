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
        Schema::table('users', function (Blueprint $table) {
            $table->string('jurusan', 20)->nullable()->after('prodi')->index();
        });

        Schema::table('form_f1_pengajuan', function (Blueprint $table) {
            $table->foreignId('recommended_dosen_id')->nullable()->after('student_id')->constrained('users')->nullOnDelete();
            $table->foreignId('plotted_by')->nullable()->after('dosen_id')->constrained('users')->nullOnDelete();
            $table->timestamp('plotted_at')->nullable()->after('approved_at');
            $table->text('revision_notes')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_f1_pengajuan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recommended_dosen_id');
            $table->dropConstrainedForeignId('plotted_by');
            $table->dropColumn(['plotted_at', 'revision_notes']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('jurusan');
        });
    }
};
