<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_menu';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_menu',
        'user_id',
        'nama_menu',
        'gambar_menu',
        'detail_menu',
        'harga_menu',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_menu', 'id_menu');
    }
}
