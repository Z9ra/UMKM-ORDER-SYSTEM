<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->string('id_menu')->primary(); // PKG001, PKG002, dst
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_menu');
            $table->string('gambar_menu')->nullable();
            $table->text('detail_menu')->nullable();
            $table->decimal('harga_menu', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
