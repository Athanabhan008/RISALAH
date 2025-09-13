<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharingProfit extends Model
{
    use HasFactory;

    protected $table = 'vw_sharing_profit';
    protected $fillable = [
        'id_projek',
        'profit_holding',
        'profit_leader',
        'profit_dirutama',
        'profit_sim',
        'profit_keuangan',
        'total_profit'
    ];

    public function sales()
    {
        return $this->belongsTo(Wapu::class, 'id_projek');
    }
}
