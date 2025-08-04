<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nonppn extends Model
{
    use HasFactory;

    protected $table = 'non_ppn';
    protected $fillable = ['angka'];

    public function sales()
    {
        return $this->belongsTo(User::class, 'id_sales');
    }
}
