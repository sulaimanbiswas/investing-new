<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all notifications (paginated).
     */
    public function index(): View
    {
        $notifications = Notification::with('user')
            ->where('is_for_admin', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::where('is_for_admin', true)
            ->where('is_read', false)
            ->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark notification as read and redirect to relevant page.
     */
    public function go(Notification $notification): RedirectResponse
    {
        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        $url = $this->resolveUrl($notification);

        return redirect($url);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Notification $notification): RedirectResponse
    {
        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(): RedirectResponse
    {
        Notification::where('is_for_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Resolve notification URL based on type.
     */
    private function resolveUrl(Notification $notification): string
    {
        $type = $notification->type;

        return match (true) {
            str_contains($type, 'deposit') => route('admin.deposits.index'),
            str_contains($type, 'withdrawal') => route('admin.withdrawals.index'),
            default => route('admin.dashboard'),
        };
    }
}
