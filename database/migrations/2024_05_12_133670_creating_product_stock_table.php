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
			DB::statement('CREATE TABLE `product_stock` (
					`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					`productId` bigint(20) unsigned NOT NULL,
					`quantity` int(11) NOT NULL,
					`userId` bigint(20) unsigned NOT NULL,
					`created_at` timestamp NULL DEFAULT NULL,
					`updated_at` timestamp NULL DEFAULT NULL,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
				
				ALTER TABLE `product_stock`
					ADD FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
					ADD FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;'
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
