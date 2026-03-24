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

        $needsAlamat = !Schema::hasColumn('orders', 'alamat');
        $needsStatus = !Schema::hasColumn('orders', 'status');

        if (!$needsAlamat && !$needsStatus) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) use ($needsAlamat, $needsStatus) {
            if ($needsAlamat) {
                $table->string('alamat')->nullable();
            }

            if ($needsStatus) {
                $table->string('status')->default('Belum Bayar');
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

        $hasAlamat = Schema::hasColumn('orders', 'alamat');
        $hasStatus = Schema::hasColumn('orders', 'status');

        if (!$hasAlamat && !$hasStatus) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) use ($hasAlamat, $hasStatus) {
            if ($hasAlamat) {
                $table->dropColumn('alamat');
            }

            if ($hasStatus) {
                $table->dropColumn('status');
            }
        });
    }
};
