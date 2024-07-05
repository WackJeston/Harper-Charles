<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	use HasFactory;

	protected $table = 'notification';

	protected $fillable = [
		'group',
		'name',
		'url',
	];

	static function getSettings() {
		$settingsPre = DB::select('SELECT
			n.id,
			n.group,
			n.name,
			COALESCE(nu.id, 0) AS notificationUserId,
			IF(nu.standard, 1, 0) AS `standard`,
			IF(nu.email, 1, 0) AS email
			FROM notification AS n
			LEFT JOIN notification_user AS nu ON nu.notificationId=n.id AND nu.userId = ?
			GROUP BY n.id', 
			[auth()->user()['id']]
		);

		$settings = [];

		foreach ($settingsPre as $i => $settingPre) {
			$settings[$settingPre->group][] = $settingPre;
		}

		return $settings;
	}
}
