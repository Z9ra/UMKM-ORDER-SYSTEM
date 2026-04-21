<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['id_menu']);
            $table->dropColumn(['id_menu', 'nama_menu', 'harga_menu']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('id_menu')->nullable();
            $table->string('nama_menu')->nullable();
            $table->decimal('harga_menu', 10, 2)->nullable();
        });
    }
};
