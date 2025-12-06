<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id(); // primary key default 'id'
            $table->string('addresses_line_01'); // wajib diisi
            $table->string('addresses_line_02')->nullable(); // opsional
            $table->string('town_city')->nullable();
            $table->string('district')->nullable();
            $table->string('country')->nullable();
            $table->string('postcode_zip')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
