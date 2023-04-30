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
        Schema::create('product_category_images', function (Blueprint $table) {
            $table->id();
						$table->foreignId('categoryId')->constrained('product_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('fileName', 100)->unique();
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
        Schema::dropIfExists('product_category_images');
    }
};
