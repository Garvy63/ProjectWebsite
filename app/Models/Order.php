<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'customer_id', 'billing_address_id', 'shipping_address_id',
        'order_date', 'subtotal', 'shipping_cost', 'total_amount',
        'status', 'payment_method', 'order_notes', 'terms_accepted'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}

