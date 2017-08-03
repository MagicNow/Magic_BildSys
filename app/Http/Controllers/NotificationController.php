<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;

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
}
