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
      Schema::create('checkout', function (Blueprint $table) {
				$table->id();
				$table->integer('userId');
				$table->integer('deliveryAddressId');
				$table->integer('billingAddressId');
				$table->string('status', 50);
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
      
    }
};
