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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('or_number')->unique();
            $table->string('nama_pelanggan');
            $table->string('no_telp');
            $table->foreignId('id_paket')->constrained('pakets')->onDelete('cascade');
            $table->integer('berat_order');
            $table->date('tgl_masuk');
            $table->date('tgl_keluar');
            $table->decimal('total', 15, 2)->default(0);
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
