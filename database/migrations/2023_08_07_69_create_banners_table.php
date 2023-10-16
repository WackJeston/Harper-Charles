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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
						$table->string('page', 100);
						$table->string('position', 100);
						$table->string('framing', 100);
						$table->string('title', 100)->nullable();
						$table->string('description', 1000)->nullable();
						$table->boolean('active')->default(0);
						$table->string('name', 255);
            $table->string('fileName', 255);
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
