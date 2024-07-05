<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewEnquiry;

class Enquiry extends Model
{
  use HasFactory;

	protected $table = 'enquiry';

  protected $fillable = [
		'type',
		'name',
		'email',
    'phone',
    'subject',
    'message',
  ];

	protected static function booted() {
		static::created(function ($self) {
			$events = DB::select('SELECT
				u.id AS userId,
				u.email AS userEmail,
				n.id AS notificationId,
				nu.standard,
				nu.email,
				nu.phone
				FROM users AS u
				INNER JOIN notification_user AS nu ON nu.userId = u.id
				INNER JOIN notification AS n ON n.id = nu.notificationId AND n.name = ? AND n.group = "Enquiries"', 
				[$self->type]
			);

			foreach ($events as $i => $event) {
				if ($event->standard) {
					NotificationEvent::create([
						'notificationId' => $event->notificationId,
						'userId' => $event->userId,
						'message' => sprintf('%s: %s', $self->name, $self->subject),
						'pageId' => $self->id
					]);
				}

				if ($event->email) {
					Mail::to($event->userEmail)->send(new NewEnquiry($self->id));

					NotificationEvent::create([
						'notificationId' => $event->notificationId,
						'userId' => $event->userId,
						'message' => sprintf('New enquiry (%s) from %s', $self->type, $self->name),
						'pageId' => $self->id
					]);
				}
				
				// if ($event->phone) {
				// 	// Send SMS
				// }
			}
    });
	}
}
