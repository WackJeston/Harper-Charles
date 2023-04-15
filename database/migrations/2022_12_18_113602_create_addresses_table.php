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
			Schema::create('addresses', function (Blueprint $table) {
				$table->id();
				$table->integer('userId');
				$table->string('type', 50)->nullable();
				$table->boolean('defaultBilling')->default(0);
				$table->boolean('defaultShipping')->default(0);
				$table->string('firstName', 100);
				$table->string('lastName', 100);
				$table->string('company', 100)->nullable();
				$table->string('line1', 200);
				$table->string('line2', 200)->nullable();
				$table->string('line3', 200)->nullable();
				$table->string('city', 100);
				$table->string('region', 100)->nullable();
				$table->string('country', 100);
				$table->string('postCode', 50);
				$table->string('phone', 20);
				$table->string('deliveryNote', 2000)->nullable();
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
        Schema::dropIfExists('addresses');
    }
};
