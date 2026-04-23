<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_tutup');
            $table->integer('total_order');
            $table->integer('total_item');
            $table->decimal('total_pendapatan', 10, 2);
            $table->integer('order_pending')->default(0);
            $table->integer('order_proses')->default(0);
            $table->integer('order_selesai')->default(0);
            $table->integer('order_batal')->default(0);
            $table->json('detail_orders');
            $table->timestamps();
        });

        Schema::create('pengaturan_tutup_buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->time('jam_tutup')->default('22:00:00');
            $table->boolean('auto_tutup')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_harian');
        Schema::dropIfExists('pengaturan_tutup_buku');
    }
};
