<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product'; // nama tabel di database
    protected $primaryKey = 'id_product'; // kolom primary key

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = true; // gunakan kolom created_at dan updated_at

    // kolom-kolom yang boleh diisi sekaligus
    protected $fillable = [
        'id_product',
        'product_name',
        'price',
        'stock',
        'satuan',
    ];
}
