<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_f5_evaluasi', function (Blueprint $table) {
            if (! Schema::hasColumn('form_f5_evaluasi', 'is_locked')) {
                $table->boolean('is_locked')->default(false)->after('submitted_at');
            }
            if (! Schema::hasColumn('form_f5_evaluasi', 'locked_at')) {
                $table->timestamp('locked_at')->nullable()->after('is_locked');
            }
        });

        Schema::table('form_f6_evaluasi', function (Blueprint $table) {
            if (! Schema::hasColumn('form_f6_evaluasi', 'is_locked')) {
                $table->boolean('is_locked')->default(false)->after('submitted_at');
            }
            if (! Schema::hasColumn('form_f6_evaluasi', 'locked_at')) {
                $table->timestamp('locked_at')->nullable()->after('is_locked');
            }
        });
    }

    public function down(): void
    {
        Schema::table('form_f5_evaluasi', function (Blueprint $table) {
            $columns = array_filter(['is_locked', 'locked_at'], fn (string $col) => Schema::hasColumn('form_f5_evaluasi', $col));
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('form_f6_evaluasi', function (Blueprint $table) {
            $columns = array_filter(['is_locked', 'locked_at'], fn (string $col) => Schema::hasColumn('form_f6_evaluasi', $col));
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};

