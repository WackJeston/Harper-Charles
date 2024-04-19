<?php

namespace App\Models;

use DB;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Aws\Exception\AwsException;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens, HasFactory;
	// use HasApiTokens, HasFactory, Notifiable;
	use Billable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'admin',
		'firstname',
		'lastname',
		'email',
		'password',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	protected static function booted() {
		static::created(function ($self) {
			if ($self->admin) {
				// self::verifyEmail($self->email);
			}
    });
	}

	public static function getOrders(int $userId = 0, string $type = 'order') {
		if ($userId == 0) {
			$user = auth()->user();
		} else {
			$user = User::find($userId);
		}

		$orders = DB::select('SELECT
			o.*,
			DATE_FORMAT(o.created_at, "%d/%m/%Y") AS `date`
			FROM orders AS o
			WHERE o.userId=?
			AND o.type=?',
			[
				$user->id,
				$type
			]
		);

		foreach ($orders as $i => $order) {
			$order->lines = DB::select('SELECT 
				ol.id AS `orderLineId`,
				p.*
				FROM order_lines AS ol
				INNER JOIN products AS p ON p.id=ol.productId
				WHERE ol.orderId=?',
				[$order->id]
			);

			foreach($order->lines AS $i2 => $line) {
				$line->variants = DB::select('SELECT
					CONCAT(pv2.title, ": ", pv.title) AS `variant`
					FROM order_line_variants AS olv
					INNER JOIN product_variants AS pv ON pv.id=olv.variantId AND pv.active=1
					INNER JOIN product_variants AS pv2 ON pv2.id=pv.parentVariantId AND pv2.active=1
					WHERE olv.orderLineId=?',
					[$line->orderLineId]
				);

				$variantsPre = [];

				foreach ($line->variants as $i3 => $variant) {
					$variantsPre[] = $variant->variant;
				}

				$line->variants = $variantsPre;
			}
		}

		return $orders;
	}

	public static function verifyEmail($email) {
		$aws = connectSes();
		
		try {
			$result = $aws->verifyEmailIdentity([
				'EmailAddress' => $email,
			]);
			var_dump($result);
		} catch (AwsException $e) {
			// output error message if fails
			echo $e->getMessage();
			echo "\n";
		}
	}
}
