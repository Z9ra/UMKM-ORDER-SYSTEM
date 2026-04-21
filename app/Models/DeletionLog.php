<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletionLog extends Model
{
    protected $fillable = [
        'user_id',
        'id_pesanan',
        'nama_pelanggan',
        'nama_menu',
        'total_pesanan',
        'total_harga',
        'status',
        'dihapus_oleh',
    ];
}
