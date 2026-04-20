<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->time('jam_input')->nullable()->after('status');
        });

        Schema::create('deletion_logs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('nomor_whatsapp');
            $table->text('detail_order');
            $table->string('status');
            $table->string('dihapus_oleh');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('jam_input');
        });
        Schema::dropIfExists('deletion_logs');
    }
};
