<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('nomor_whatsapp')->nullable()->after('nama_pelanggan');
            $table->text('alamat')->nullable()->after('nomor_whatsapp');
            $table->enum('tipe_order', ['online', 'onsite'])->default('online')->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['nomor_whatsapp', 'alamat', 'tipe_order']);
        });
    }
};
