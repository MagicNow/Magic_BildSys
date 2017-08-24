<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;
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

    public static function marcarLido($workflow_tipo_id, $id_dinamico)
    {
        $notification = Notification::where('notifiable_type','App\\Models\\User')
            ->where('notifiable_id',auth()->id())
            ->where('data','LIKE','%"workflow_tipo_id":'. $workflow_tipo_id .',"id_dinamico":'. $id_dinamico .',%')
            ->whereNull('read_at')
            ->first();

        if($notification) {
            Notification::where('notifiable_type','App\\Models\\User')
                ->where('notifiable_id',auth()->id())
                ->where('data','LIKE','%"workflow_tipo_id":'. $workflow_tipo_id .',"id_dinamico":'. $id_dinamico .',%')
                ->whereNull('read_at')
                ->update([
                    'read_at' => Carbon::now()
                ]);
        }
        
        return $notification;
    }

    public static function marcarFeito($workflow_tipo_id, $id_dinamico)
    {
        $notification = Notification::where('notifiable_type','App\\Models\\User')
            ->where('notifiable_id',auth()->id())
            ->where('data','LIKE','%"workflow_tipo_id":'. $workflow_tipo_id .',"id_dinamico":'. $id_dinamico .',%')
            ->where('data','LIKE','%"done":0%')
            ->first();

        if($notification) {
            $data = $notification->data;
            $data['done'] = 1;
            $notification->data = $data;
            $notification->save();
        }
        self::marcarLido($workflow_tipo_id, $id_dinamico);

        return $notification;
    }
}
