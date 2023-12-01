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
        Schema::create('content', function (Blueprint $table) {
            $table->id();
						$table->string('page', 100);
						$table->string('position', 100);
						$table->string('title', 100)->nullable();
						$table->string('subtitle', 255)->nullable();
						$table->string('description', 5000)->nullable();
						$table->boolean('active')->default(0);
            $table->foreignId('assetId')->constrained('asset')->onUpdate('cascade')->onDelete('cascade')->nullable();
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
