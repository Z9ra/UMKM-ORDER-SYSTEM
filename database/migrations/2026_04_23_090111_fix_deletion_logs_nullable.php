<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deletion_logs', function (Blueprint $table) {
            $table->string('id_pesanan')->nullable()->change();
            $table->string('nama_pelanggan')->nullable()->change();
            $table->string('nama_menu')->nullable()->change();
            $table->integer('total_pesanan')->nullable()->change();
            $table->decimal('total_harga', 10, 2)->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->string('dihapus_oleh')->nullable()->change();
        });
    }

    public function down(): void {}
};
