<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
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
     * Return the current unread notification count as JSON (used for audio polling).
     */
    public function checkUnread(): JsonResponse
    {
        $latestNotificationModels = Notification::with('user')
            ->where('is_for_admin', true)
            ->latest()
            ->take(10)
            ->get();

        $latestNotifications = $latestNotificationModels
            ->map(function (Notification $notification): array {
                $type = $notification->type ?? '';

                return [
                    'id' => $notification->id,
                    'go_url' => route('admin.notifications.go', $notification),
                    'title' => $notification->title,
                    'message' => Str::limit($notification->message, 80),
                    'user_name' => $notification->user?->name ?? 'User',
                    'time_ago' => $notification->created_at?->diffForHumans(),
                    'is_read' => (bool) $notification->is_read,
                    'icon' => str_contains($type, 'deposit')
                        ? 'money-bill-wave'
                        : (str_contains($type, 'withdrawal') ? 'wallet' : 'bell'),
                ];
            })
            ->values();

        $unreadCount = Notification::where('is_for_admin', true)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $latestNotifications,
            'has_notifications' => $latestNotifications->isNotEmpty(),
            'latest_notification_id' => $latestNotificationModels->first()?->id,
        ]);
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
            str_contains($type, 'user_registered') => route('admin.users.show', $notification->user_id),
            default => route('admin.dashboard'),
        };
    }
}
