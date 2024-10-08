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
		Schema::create('order_lines', function (Blueprint $table) {
			$table->id();
			$table->foreignId('orderId')->constrained('orders')->onUpdate('cascade')->onDelete('cascade');
			$table->foreignId('productId')->constrained('products')->onUpdate('cascade')->onDelete('cascade');
			$table->integer('quantity')->default('0');
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
		Schema::dropIfExists('order_lines');
	}
};
