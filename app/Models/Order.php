<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // TAMBAHKAN INI - Karena primary key adalah order_id, bukan id
    protected $primaryKey = 'order_id';
    
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'shipping_address',
        'city',
        'postcode',
        'country',
        'payment_method',
        'payment_status',
        'subtotal',
        'shipping_cost',
        'total',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}