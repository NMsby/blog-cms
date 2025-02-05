<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationBell extends Component
{
    public int $unreadCount;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->unreadCount = auth()->user()?->unreadNotifications->count() ?? 0;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notification-bell');
    }
}
