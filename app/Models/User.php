<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens, HasFactory, Notifiable;
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
		static::deleting(function ($self) {
			$cartItems = Cart::where('userId', $self->id)->get();
			
			foreach ($cartItems as $i => $item) {
				CartVariants::where('cartId', $item->id)->delete();
			}

			Cart::where('userId', $self->id)->delete();
			Address::where('userId', $self->id)->delete();
			Checkout::where('userId', $self->id)->delete();
		});
	}
}
