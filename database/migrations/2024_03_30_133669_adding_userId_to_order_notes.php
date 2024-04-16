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
			DB::statement('ALTER TABLE order_notes
					ADD COLUMN userId bigint(20) unsigned NOT NULL AFTER orderId
					ADD COLUMN `primary` tinyint(1) NOT NULL DEFAULT 0 AFTER `admin`;
				
				ALTER TABLE order_notes
					ADD FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;'
			);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_notes', function (Blueprint $table) {
            //
        });
    }
};
