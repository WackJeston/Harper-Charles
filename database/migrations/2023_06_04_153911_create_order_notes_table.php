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
		Schema::create('order_notes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('orderId')->constrained('orders')->onUpdate('cascade')->onDelete('cascade');
			$table->boolean('admin')->default(0);
			$table->string('note', 4000);
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
		Schema::dropIfExists('order_notes');
	}
};
