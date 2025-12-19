<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class FriendAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public User $friend;

    public function __construct(User $user, User $friend)
    {
        $this->user   = $user;   // кто добавил
        $this->friend = $friend; // кого добавили
    }
}