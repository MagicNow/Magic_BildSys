<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends AppBaseController
{
    protected $notificationRepository;

    /**
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function index()
    {
        $notifications = $this->notificationRepository->unreadFromUser(auth()->id());

        return $notifications;
    }

    public function markAsRead($id)
    {
        return response()->json([
            'success' => $this->notificationRepository->markAsRead($id)
        ]);
    }

    public function notificacoesLidas(){
        $notificacoes = Notification::where('notifiable_id', Auth::id())
            ->whereNull('read_at')
            ->get();

        foreach ($notificacoes as $notificacao) {
            $notificacao->update(['read_at' => Carbon::now()]);
        }
    }
}
