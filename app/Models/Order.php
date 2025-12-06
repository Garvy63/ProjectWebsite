<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    // Sesuaikan dengan kolom yang Anda buat di Migration
    protected $fillable = [
        'user_id', 'session_id', 'email', 'payment_method', 'payment_status',
        'first_name', 'last_name', 'shipping_address', 'city', 'postcode', 'country',
        'subtotal', 'shipping_cost', 'total',
    ];
    
    // Relasi ke Order Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}