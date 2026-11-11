<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserProfileComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public $user;
    public $onlineUserIds;

    public function __construct($user, $onlineUserIds = [])
    {
        $this->user = $user;
        $this->onlineUserIds = $onlineUserIds;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-profile-component');
    }
}
