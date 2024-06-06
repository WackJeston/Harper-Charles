<?php
namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use DB;
use App\Models\Notification;
use App\Models\NotificationUser;


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
}
