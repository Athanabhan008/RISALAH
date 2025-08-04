<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Swasta extends Model
{
    use HasFactory;

    protected $table = 'swasta';
    protected $fillable = ['angka'];

    public function sales()
    {
        return $this->belongsTo(User::class, 'id_sales');
    }
}
