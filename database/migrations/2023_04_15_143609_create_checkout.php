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
				$table->foreignId('userId')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
				$table->foreignId('deliveryAddressId')->nullable()->constrained('addresses')->onUpdate('cascade')->onDelete('cascade');
				$table->foreignId('billingAddressId')->nullable()->constrained('addresses')->onUpdate('cascade')->onDelete('cascade');
				$table->string('paymentMethodId', 200)->nullable()->default(null);
				$table->decimal('total', 9, 2)->default(0);
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
