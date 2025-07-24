<?php

namespace App\Http\Controllers;

use App\Models\NotificationPreference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationSettingsController extends Controller
{
    /**
     * Display the user's notification settings.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Eager load the notifiable items to avoid N+1 queries in the view
        $mutedItems = NotificationPreference::where('user_id', $user->id)
            ->with('notifiable')
            ->get();

        return view('notifications.settings', [
            'user' => $user,
            'mutedItems' => $mutedItems,
        ]);
    }

    /**
     * Toggle the global notification setting for the user.
     */
    public function toggleGlobal(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $user->notifications_enabled = $request->input('enabled', false);
        $user->save();

        return back()->with('success', 'Paramètres de notification mis à jour.');
    }

    /**
     * Toggle the mute status for a specific notifiable item.
     */
    public function toggleMute(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'notifiable_type' => 'required|string',
            'notifiable_id' => 'required|integer',
        ]);

        $user = Auth::user();

        NotificationPreference::updateOrCreate(
            [
                'user_id' => $user->id,
                'notifiable_type' => $validated['notifiable_type'],
                'notifiable_id' => $validated['notifiable_id'],
            ],
            [
                // If it exists, we toggle `is_muted`. If not, we create it as muted.
                'is_muted' => !$user->isMuted($validated['notifiable_type'], $validated['notifiable_id'])
            ]
        );

        return back()->with('success', 'Préférence de notification mise à jour.');
    }
}
