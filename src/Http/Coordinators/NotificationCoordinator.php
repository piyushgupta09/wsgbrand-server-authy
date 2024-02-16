<?php

namespace Fpaipl\Authy\Http\Coordinators;

use Illuminate\Http\Request;
use Fpaipl\Panel\Http\Responses\ApiResponse;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Authy\Http\Resources\NotificationResource;

class NotificationCoordinator extends Coordinator
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $perPage = $request->perpage ?? 20;
        $search = $request->search ?? null;
        $status = $request->status ?? null;

        $notifications = $user->notifications()
            ->when($search, function ($query, $search) {
                return $query->where('data->body', 'like', "%$search%");
            })
            ->when($status, function ($query, $status) {
                // if $status is unread then we need to check for read_at is null, else we need to check for read_at is not null
                if ($status === 'unread') {
                    return $query->whereNull('read_at');
                }
                if ($status === 'read') {
                    return $query->whereNotNull('read_at');
                }
            })
            ->paginate($perPage);

        return ApiResponse::success([
            'data' => NotificationResource::collection($notifications),
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
        return ApiResponse::success(
            NotificationResource::collection($notifications)
        );
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
        // $channel = $user->isBrand() ? $user->uuid : $user->party->uuid;

        if ($user->isBrand()) {
            $event ='brand-event';
        }

        if ($user->isParty()) {
            $event = 'party-event';
        }
    
        return ApiResponse::success([
            'event' => $event,
            // 'channel' => $channel,
            'channel' => $user->uuid,
            'key' => config('pusher.app_key'),
        ]);
    }

}