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
        if (!Schema::hasTable('orders')) {
            return;
        }

        $needsDibayarkan = !Schema::hasColumn('orders', 'dibayarkan');
        $needsKembalian = !Schema::hasColumn('orders', 'kembalian');

        if (!$needsDibayarkan && !$needsKembalian) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) use ($needsDibayarkan, $needsKembalian) {
            if ($needsDibayarkan) {
                $table->decimal('dibayarkan', 15, 2)->default(0);
            }

            if ($needsKembalian) {
                $table->decimal('kembalian', 15, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        $hasDibayarkan = Schema::hasColumn('orders', 'dibayarkan');
        $hasKembalian = Schema::hasColumn('orders', 'kembalian');

        if (!$hasDibayarkan && !$hasKembalian) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) use ($hasDibayarkan, $hasKembalian) {
            if ($hasDibayarkan) {
                $table->dropColumn('dibayarkan');
            }

            if ($hasKembalian) {
                $table->dropColumn('kembalian');
            }
        });
    }
};
