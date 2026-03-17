<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vwmenu extends Model
{
    use HasFactory;

    protected $table = 'vw_menu';
    protected $fillable = [
        'id_jenis',
        'nama_menu',
        'keterangan',
        'harga',
        'created_at'
    ];
}
