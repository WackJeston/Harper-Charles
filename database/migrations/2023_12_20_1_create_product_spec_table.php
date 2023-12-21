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
        Schema::create('product_spec', function (Blueprint $table) {
            $table->id();
						$table->foreignId('productId')->constrained('products')->onUpdate('cascade')->onDelete('cascade');
						$table->string('label', 255);
						$table->string('value', 255);
						$table->string('description', 1000)->nullable();
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
        Schema::dropIfExists('contact');
    }
};
