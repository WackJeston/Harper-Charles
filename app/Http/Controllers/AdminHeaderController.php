<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Notification;
use App\Models\NotificationUser;


class AdminHeaderController extends AdminController
{
  public function toggleNotification(int $id, int $notificationUserId, string $type)
  {
		$group = Notification::select('group', 'name')->where('id', $id)->get()[0];

    if ($record = NotificationUser::find($notificationUserId)) {
			$record->delete();

			return json_encode([0, $group->group, $group->name]);
		
		} else {
			$record = new NotificationUser;
			$record->notificationId = $id;
			$record->userId = auth()->user()->id;

			if ($type == 'email') {
				$record->email = 1;
			} elseif ($type == 'phone') {
				$record->phone = 1;
			} else {
				$record->standard = 1;
			}

			$record->save();

			return json_encode([1, $group->group, $group->name, $record->id]);
		}
  }
}
