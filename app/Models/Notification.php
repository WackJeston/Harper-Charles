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

	static function getNotifications() {
		return DB::select('SELECT
			ne.*,
			n.group,
			n.name,
			IF(ISNULL(ne.pageId), n.url, CONCAT(n.url, "/", ne.pageId)) AS link
			FROM notification_event AS ne
			INNER JOIN notification AS n ON n.id = ne.notificationId
			WHERE ne.userId = ?
			ORDER BY ne.created_at DESC',
			[auth()->user()->id]
		);
	}

	public static function limitCheck():bool {
		$records = DB::select('SELECT
			COUNT(*) AS count
			FROM notification_event
			WHERE userId = ?',
			[auth()->user()->id]	
		);

		return $records[0]->count < 99;
	}
}
