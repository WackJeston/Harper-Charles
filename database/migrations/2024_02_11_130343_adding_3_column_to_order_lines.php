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
        Schema::table('order_lines', function (Blueprint $table) {
          $table->string('orbitalVisionConfigurationId', 255)->default(null)->after('productId');
					$table->string('orbitalVisionFileName', 255)->default(null)->after('orbitalVisionConfigurationId');
					$table->decimal('price', 9, 2)->default(0)->after('quantity');
					$table->decimal('total', 9, 2)->default(0)->after('price');
					$table->foreignId('assetId')->constrained('asset')->onUpdate('cascade')->onDelete('cascade')->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_lines', function (Blueprint $table) {
            //
        });
    }
};
