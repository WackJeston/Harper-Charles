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
    Schema::create('product_variants', function (Blueprint $table) {
      $table->id();
			$table->foreignId('parentVariantId')->nullable()->constrained('product_variants')->onUpdate('cascade')->onDelete('cascade');
      $table->string('title', 100);
			$table->foreignId('assetId')->constrained('asset')->onUpdate('cascade')->onDelete('cascade')->nullable();
			$table->string('colour', 100);
      $table->boolean('active')->default(0);
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
    Schema::dropIfExists('product_variants');
  }
};
