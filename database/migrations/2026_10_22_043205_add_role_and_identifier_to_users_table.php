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
            $table->string('role', 30)->default('mahasiswa')->after('name'); // mahasiswa, dosen, dudi, admin
            $table->string('identifier', 50)->nullable()->unique()->after('role'); // NIM, NIP, NRK, or ID
            $table->string('prodi', 100)->nullable()->after('identifier');
            $table->string('phone', 25)->nullable()->after('prodi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'identifier', 'prodi', 'phone']);
        });
    }
};
