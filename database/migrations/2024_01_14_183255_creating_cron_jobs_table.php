<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
			Schema::create('cron_jobs', function (Blueprint $table) {
				$table->id();
				$table->string('command', 255);
				$table->string('schedule', 255);
				$table->timestamp('lastRunOn', $precision = 0);
				$table->string('lastRunTime', 255);
				$table->boolean('active')->default(0);
				$table->timestamps();
			});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
