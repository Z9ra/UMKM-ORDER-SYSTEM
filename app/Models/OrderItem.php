<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'id_pesanan',
        'id_menu',
        'nama_menu',
        'harga_menu',
        'jumlah',
        'subtotal',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_pesanan', 'id_pesanan');
    }
}
