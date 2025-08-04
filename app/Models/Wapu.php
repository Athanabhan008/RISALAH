<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wapu extends Model
{
    use HasFactory;

    protected $table = 'prwapus';
    protected $fillable = [
        'angka',
        'persentase_incentive',
        'incentive_fe001a',
        'persentase_fe001a',
        'approval',
        'status',
        'validasi_payment',
        'pph_bank_fee',
        'subtotal_price',
        'subtotal_cost',
        'subtotal_sp2d',
        'jumlah_ppn',
        'total_vat',
        'total_margin'
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'id_sales');
    }
}
