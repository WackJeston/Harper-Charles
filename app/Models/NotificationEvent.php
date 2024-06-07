<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationEvent extends Model
{
    use HasFactory;

    protected $table = 'notification_event';

    protected $fillable = [
      'notificationId',
      'userId',
      'message',
    ];
}
