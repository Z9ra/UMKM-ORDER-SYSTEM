<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pesanan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_pesanan',
        'user_id',
        'nama_pelanggan',
        'nomor_whatsapp',
        'alamat',
        'tipe_order',
        'total_pesanan',
        'total_harga',
        'status',
        'jam_input',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'id_pesanan', 'id_pesanan');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
