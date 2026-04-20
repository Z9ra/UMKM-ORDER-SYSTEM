<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pelanggan',
        'nomor_whatsapp',
        'alamat',
        'detail_order',
        'total_harga',
        'status',
        'jam_input',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
