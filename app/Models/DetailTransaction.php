<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    protected $table = 'detail_transactions';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_transaction',
        'id_product',
        'quantity',
        'subtotal',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
