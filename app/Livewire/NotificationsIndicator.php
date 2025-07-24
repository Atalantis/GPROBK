<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class NotificationsIndicator extends Component
{
    public $unreadCount;
    public $notifications;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = auth()->user();
        $this->unreadCount = $user->unreadNotifications()->count();
        $this->notifications = $user->notifications()->limit(5)->get();
    }

    public function getListeners(): array
    {
        $userId = auth()->id();
        return [
            "echo-private:App.Models.User.{$userId},.Illuminate\\Notifications\\Events\\DatabaseNotificationCreated" => 'loadNotifications',
        ];
    }

    public function render(): View
    {
        return view('livewire.notifications-indicator');
    }
}
