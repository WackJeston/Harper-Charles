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
        Schema::create('notification_user', function (Blueprint $table) {
            $table->id();
						$table->foreignId('notificationId')->constrained('notification')->onUpdate('cascade')->onDelete('cascade');
						$table->foreignId('userId')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
						$table->boolean('standard')->default(0);
						$table->boolean('email')->default(0);
						$table->boolean('phone')->default(0);
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
        Schema::dropIfExists('settings');
    }
};
