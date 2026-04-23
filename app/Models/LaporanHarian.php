<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    protected $table = 'laporan_harian';

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_tutup',
        'total_order',
        'total_item',
        'total_pendapatan',
        'order_pending',
        'order_proses',
        'order_selesai',
        'order_batal',
        'detail_orders',
    ];

    protected $casts = [
        'detail_orders' => 'array',
        'tanggal' => 'date',
    ];
}
