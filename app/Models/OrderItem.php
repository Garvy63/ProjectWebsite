<?php

// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price',
    ];
    
    // Relasi ke Order (kebalikan)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke Produk
    public function product()
    {
        // Asumsi nama model produk Anda adalah App\Models\Product
        return $this->belongsTo(Product::class);
    }
}