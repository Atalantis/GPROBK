<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index(): View
    {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read and redirect.
     */
    public function markAsRead(string $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('dashboard'));
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
