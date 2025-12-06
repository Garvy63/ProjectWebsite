<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id'; // WAJIB
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
    'user_id','address_id','subtotal','shipping_cost','total','notes','status'
];
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
