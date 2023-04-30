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
        Schema::create('landing_zone_carousels', function (Blueprint $table) {
          $table->id();
					$table->foreignId('landingZoneId')->constrained('landing_zones')->onUpdate('cascade')->onDelete('cascade');
          $table->string('title', 100)->nullable();
          $table->string('subtitle', 100)->nullable();
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
      Schema::dropIfExists('landing_zone_carousels');
    }
};
