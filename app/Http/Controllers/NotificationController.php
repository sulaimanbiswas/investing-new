<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_for_admin', false)
            ->latest()
            ->paginate(15);

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_for_admin', false)
            ->where('is_read', false)
            ->count();

        return view('user.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Notification $notification)
    {
        $this->authorizeNotification($notification);
        $notification->update(['is_read' => true]);

        return back();
    }

    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_for_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }

    public function go(Notification $notification)
    {
        $this->authorizeNotification($notification);
        if (! $notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return Redirect::to($this->resolveUrl($notification));
    }

    private function authorizeNotification(Notification $notification): void
    {
        abort_unless($notification->user_id === Auth::id(), 403);
    }

    private function resolveUrl(Notification $notification): string
    {
        $data = $notification->data ?? [];
        $type = $notification->type;

        return match ($type) {
            'deposit_pending', 'deposit_completed', 'deposit_rejected' => route('deposit.records'),
            'withdrawal_submitted', 'withdrawal_approved', 'withdrawal_rejected' => route('withdrawal.records'),
            default => route('profile.home'),
        };
    }
}
