<?php

// app/Models/CartItem.php

namespace App\Models;

use App\Models\Product; // WAJIB
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    // ...

    /**
     * Relasi ke Product
     */
    // Tambahkan tipe return, dan hapus 'related:'
    public function product(): BelongsTo 
    {
        // Ganti `$this->belongsTo(related: Product::class);`
      return $this->belongsTo(Product::class);
    }

    // Hitung total harga per item
    public function getTotalAttribute(): float|int // float|int membutuhkan PHP 8.0+
{
    return $this->quantity * $this->price;
}
}
