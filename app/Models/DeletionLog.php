<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletionLog extends Model
{
    protected $fillable = [
        'user_id',
        'nama_pelanggan',
        'nomor_whatsapp',
        'detail_order',
        'status',
        'dihapus_oleh',
    ];
}
