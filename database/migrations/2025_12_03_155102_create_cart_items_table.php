<?php

// database/migrations/..._create_cart_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->nullable()->constrained(); // Jika keranjang terikat user
            $table->string('session_id')->nullable(); // Untuk keranjang tamu (guest)
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 8, 2); // Harga saat produk dimasukkan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
