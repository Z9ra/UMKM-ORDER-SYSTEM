<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanTutupBuku extends Model
{
    protected $table = 'pengaturan_tutup_buku';

    protected $fillable = [
        'user_id',
        'jam_tutup',
        'auto_tutup',
    ];
}
