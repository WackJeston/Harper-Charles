<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_variants', function (Blueprint $table) {
            $table->id();
						$table->foreignId('cartId')->constrained('cart')->onUpdate('cascade')->onDelete('cascade');
						$table->foreignId('variantId')->constrained('product_variants')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_variants');
    }
};
