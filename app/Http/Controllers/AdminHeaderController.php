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
			$record->{$type} = !$record->{$type};

			if ($record->standard == 0 && $record->email == 0 && $record->phone == 0) {
				$record->delete();
				return json_encode([0, $group->group, $group->name]);
			}
		
		} else {
			$record = new NotificationUser;
			$record->notificationId = $id;
			$record->userId = auth()->user()->id;
			$record->{$type} = 1;
		}

		$record->save();

		return json_encode([1, $group->group, $group->name, $record->id]);
  }
}
