<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->string('id_pesanan');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('orders')->onDelete('cascade');
            $table->string('id_menu');
            $table->foreign('id_menu')->references('id_menu')->on('menus')->onDelete('cascade');
            $table->string('nama_menu');
            $table->decimal('harga_menu', 10, 2);
            $table->integer('jumlah');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
