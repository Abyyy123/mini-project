<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'master_products';
    protected $primaryKey = 'product_id';
    public $timestamps = true;

    protected $fillable = [
        'product_code', 'product_name', 'price', 'stock_quantity', 'is_active'
    ];
}