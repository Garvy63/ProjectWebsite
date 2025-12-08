<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Tambahkan kolom customer_id setelah id
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            
            // Atau jika Anda ingin customer_id sama dengan id:
            // Tidak perlu kolom baru, cukup gunakan id sebagai customer_id
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('customer_id');
        });
    }
};