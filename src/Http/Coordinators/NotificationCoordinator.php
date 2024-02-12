<?php

namespace Fpaipl\Authy\Http\Coordinators;

use Illuminate\Http\Request;
use Fpaipl\Panel\Http\Responses\ApiResponse;
use Fpaipl\Panel\Http\Coordinators\Coordinator;

class NotificationCoordinator extends Coordinator
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(10);
        return ApiResponse::success([
            'data' => $notifications,
            'pagination' => [
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'from' => $notifications->firstItem(),
                'to' => $notifications->lastItem(),
            ]
        ]);
    }

    public function unread()
    {
        /** @var User $user */
        $user = auth()->user();
        $notifications = $user->unreadNotifications()->get();
        return ApiResponse::success($notifications);
    }

    public function markRead(Request $request, $notification)
    {
        /** @var User $user */
        $user = auth()->user();
        $user->notifications()->where('id', $notification)->update(['read_at' => now()]);
        return ApiResponse::success('Notification marked as read');
    }

    public function markAllRead(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $user->unreadNotifications()->update(['read_at' => now()]);
        return ApiResponse::success('All notifications marked as read');
    }

    public function pusherAuth(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $channel = $user->isBrand() ? $user->uuid : $user->party->uuid;

        if ($user->isBrand()) {
            $event ='brand-event';
        }

        if ($user->isParty()) {
            $event = 'party-event';
        }
    
        return ApiResponse::success([
            'event' => $event,
            'channel' => $channel,
            'key' => config('pusher.app_key'),
        ]);
    }

}