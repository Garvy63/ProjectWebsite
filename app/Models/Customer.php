<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email_id',
        'phone_number',
        'company_name',
        'shipping_address',
        'city',
        'postcode',
        'country',
    ];


    // Relasi: Satu Customer Detail dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



