<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyClosing extends Model
{
    use HasFactory;
    protected $fillable = [
    'id_kasir', 'tanggal', 'modal_awal', 'cash_total', 'qris_total', 'total', 'auto_closed'
];
public function kasir()
{
    return $this->belongsTo(User::class, 'id_kasir');
}
}




