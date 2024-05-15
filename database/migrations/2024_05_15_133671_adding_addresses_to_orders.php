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
			DB::statement('ALTER TABLE orders
				ADD COLUMN billingFirstName varchar(100) NULL DEFAULT NULL AFTER stripeReceipt,
				ADD COLUMN billingLastName varchar(100) NULL DEFAULT NULL AFTER billingFirstName,
				ADD COLUMN billingCompany varchar(100) NULL DEFAULT NULL AFTER billingLastName,
				ADD COLUMN billingLine1 varchar(200) NULL DEFAULT NULL AFTER billingCompany,
				ADD COLUMN billingLine2 varchar(200) NULL DEFAULT NULL AFTER billingLine1,
				ADD COLUMN billingLine3 varchar(200) NULL DEFAULT NULL AFTER billingLine2,
				ADD COLUMN billingCity varchar(100) NULL DEFAULT NULL AFTER billingLine3,
				ADD COLUMN billingRegion varchar(100) NULL DEFAULT NULL AFTER billingcity,
				ADD COLUMN billingCountry varchar(2) NULL DEFAULT NULL AFTER billingregion,
				ADD COLUMN billingPostCode varchar(50) NULL DEFAULT NULL AFTER billingcountry,
				ADD COLUMN billingPhone varchar(20) NULL DEFAULT NULL AFTER billingpostCode,
				ADD COLUMN billingEmail varchar(100) NULL DEFAULT NULL AFTER billingphone,
				ADD COLUMN deliveryFirstName varchar(100) NULL DEFAULT NULL AFTER billingemail,
				ADD COLUMN deliveryLastName varchar(100) NULL DEFAULT NULL AFTER deliveryFirstName,
				ADD COLUMN deliveryCompany varchar(100) NULL DEFAULT NULL AFTER deliveryLastName,
				ADD COLUMN deliveryLine1 varchar(200) NULL DEFAULT NULL AFTER deliveryCompany,
				ADD COLUMN deliveryLine2 varchar(200) NULL DEFAULT NULL AFTER deliveryLine1,
				ADD COLUMN deliveryLine3 varchar(200) NULL DEFAULT NULL AFTER deliveryLine2,
				ADD COLUMN deliveryCity varchar(100) NULL DEFAULT NULL AFTER deliveryLine3,
				ADD COLUMN deliveryRegion varchar(100) NULL DEFAULT NULL AFTER deliverycity,
				ADD COLUMN deliveryCountry varchar(2) NULL DEFAULT NULL AFTER deliveryregion,
				ADD COLUMN deliveryPostCode varchar(50) NULL DEFAULT NULL AFTER deliverycountry,
				ADD COLUMN deliveryPhone varchar(20) NULL DEFAULT NULL AFTER deliverypostCode,
				ADD COLUMN deliveryEmail varchar(100) NULL DEFAULT NULL AFTER deliveryphone;'
			);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
