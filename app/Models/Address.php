<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'address_line_01',
        'address_line_02',
        'town_city',
        'district',
        'country',
        'postcode_zip',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function user()
    {
        return $this->hasOne(User::class, 'address_id');
    }
}
