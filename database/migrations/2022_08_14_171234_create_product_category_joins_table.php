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
    Schema::create('product_category_joins', function (Blueprint $table) {
      $table->id();
			$table->foreignId('productId')->constrained('products')->onUpdate('cascade')->onDelete('cascade');
			$table->foreignId('categoryId')->constrained('product_categories')->onUpdate('cascade')->onDelete('cascade');
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
    Schema::dropIfExists('product_category_joins');
  }
};
