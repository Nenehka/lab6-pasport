<?php

namespace App\Listeners;

use App\Events\FriendAdded;

class CreateMutualFriendship
{
    public function handle(FriendAdded $event): void
    {
        $user   = $event->user;
        $friend = $event->friend;

        // создаём обратную связь friend -> user
        $friend->friends()->syncWithoutDetaching([$user->id]);
    }
}