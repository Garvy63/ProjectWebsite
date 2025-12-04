<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    
    protected $fillable = [
        'product_name',
        'category',
        'product_image',  // ← TAMBAHKAN INI
        'unit_price'
    ];

    public $timestamps = false;
}