<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id_pesanan')->primary(); // OD001, OD002, dst
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_pelanggan');
            $table->string('id_menu');
            $table->foreign('id_menu')->references('id_menu')->on('menus')->onDelete('cascade');
            $table->string('nama_menu');
            $table->decimal('harga_menu', 10, 2);
            $table->integer('total_pesanan');
            $table->decimal('total_harga', 10, 2);
            $table->enum('status', ['pending', 'proses', 'selesai', 'batal'])->default('pending');
            $table->time('jam_input')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
