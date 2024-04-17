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
			DB::statement('CREATE TABLE 
			`payments` (
					`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					`orderId` bigint(20) unsigned DEFAULT NULL,
					`stripeReference` varchar(255) NOT NULL,
					`type` varchar(255) DEFAULT NULL,
					`status` varchar(255) DEFAULT NULL,
					`detail` VARCHAR(255) NULL DEFAULT NULL,
					`method` VARCHAR(255) NULL DEFAULT NULL,
					`amount` decimal(9,2) NOT NULL DEFAULT 0.00,
					`captured` decimal(9,2) NOT NULL DEFAULT 0.00,
					`created_at` timestamp NULL DEFAULT NULL,
					`updated_at` timestamp NULL DEFAULT NULL,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
				
				ALTER TABLE payments 
					ADD FOREIGN KEY (`orderId`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;'
			);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
