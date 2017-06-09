<?php

namespace App\Repositories;

use App\Models\Notification;
use InfyOm\Generator\Common\BaseRepository;
use Carbon\Carbon;

class NotificationRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Notification::class;
    }

    public function unreadFromUser($user_id)
    {
        return $this->model
            ->where('notifiable_id', $user_id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function markAsRead($notification_id)
    {
        $notification = $this->find($notification_id);
        $notification->update(['read_at' => Carbon::now()]);

        return $notification;
    }
}
