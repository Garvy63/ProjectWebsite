<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // App\Models\Product.php
    protected $primaryKey = 'product_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'product_name',
        'category',
        'product_image',
        'unit_price',
        'description',
        'stock'
    ];

    public $timestamps = false;
}
