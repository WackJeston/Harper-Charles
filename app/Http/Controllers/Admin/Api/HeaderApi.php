<?php
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use DB;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\NotificationEvent;


class HeaderApi extends Controller
{
  public function toggleNotification(int $id, int $notificationUserId, string $type)
  {
		$group = Notification::select('group', 'name')->where('id', $id)->get()[0];

    if ($record = NotificationUser::find($notificationUserId)) {
			$record->{$type} = !$record->{$type};

			if ($record->standard == 0 && $record->email == 0 && $record->phone == 0) {
				$record->delete();
			} else {
				$record->save();
			}
		
		} else {
			$record = new NotificationUser;
			$record->notificationId = $id;
			$record->userId = auth()->user()->id;
			$record->{$type} = 1;
			$record->save();
		}

		return json_encode(Notification::getSettings());
  }

	public function reloadNotifications() {
		return json_encode(DB::select('SELECT
			ne.*,
			n.group,
			n.name,
			IF(ISNULL(ne.pageId), n.url, CONCAT(n.url, "/", ne.pageId)) AS link
			FROM notification_event AS ne
			INNER JOIN notification AS n ON n.id = ne.notificationId
			WHERE ne.userId = ?
			ORDER BY ne.created_at DESC',
			[auth()->user()->id]
		));
	}

	public function deleteNotification(int $id):bool {
		if ($event = NotificationEvent::find($id)) {			
			$event->delete();
		
			return true;
		}

		return false;
	}

	public function deleteAllNotifications() {
		NotificationEvent::where('userId', auth()->user()->id)->delete();
	}
}
