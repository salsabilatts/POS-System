<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id_transaction';
    public $timestamps = true;

    protected $fillable = [
        'transaction_date',
        'total_amount',
        'payment_method',
        'paid_amount',
        'change_amount',
        'id_user',
        'discount_type',
        'discount_amount',
        'tax',
    ];

    public function details()
    {
        return $this->hasMany(DetailTransaction::class, 'id_transaction');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

}
