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
      Schema::create('product_images', function (Blueprint $table) {
        $table->id();
				$table->foreignId('productId')->constrained('products')->onUpdate('cascade')->onDelete('cascade');
        $table->foreignId('assetId')->constrained('asset')->onUpdate('cascade')->onDelete('cascade');
        $table->boolean('primary');
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
      Schema::dropIfExists('product_images');
    }
};
