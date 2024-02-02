<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::drop('cart');
			Schema::drop('cart_variants');
			Schema::drop('checkout');
			Schema::drop('checkout_productss');
			Schema::drop('checkout_product_variants');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
