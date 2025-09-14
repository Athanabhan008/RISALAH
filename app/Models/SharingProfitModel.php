<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharingProfitModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_pengajuan_admin',
        'id_admin',
        'nama_admin',
        'is_approve',
        'id_approve',
        'user_approve'
    ];
    protected $table = 'sharing_profit';
}
