<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deletion_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('id_pesanan');
            $table->string('nama_pelanggan');
            $table->string('nama_menu');
            $table->integer('total_pesanan');
            $table->decimal('total_harga', 10, 2);
            $table->string('status');
            $table->string('dihapus_oleh');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deletion_logs');
    }
};
