<?php

use DB;
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
			DB::statement('ALTER TABLE products 
				ADD COLUMN `stock` bigint(20) DEFAULT NULL AFTER `maxQuantity`,
				ADD COLUMN `startDate` timestamp NULL DEFAULT NULL AFTER `stock`,
				ADD COLUMN `endDate` timestamp NULL DEFAULT NULL AFTER `startDate`;'
			);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
