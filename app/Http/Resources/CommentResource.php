<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request): array
    {
        $owner   = $this->user;   // автор комментария
        $album   = $this->album;  // связанный альбом
        $current = $request->user();

        $isFriend = $current
            ? $current->friends()->where('friend_id', $owner->id)->exists()
            : false;

        return [
            'id'         => $this->id,
            'text'       => $this->text,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),

            'user' => [
                'id'   => $owner->id,
                'name' => $owner->name,
            ],

            // данные основной сущности (альбома) в ресурсе комментария
            'album' => [
                'id'          => $album->id,
                'title'       => $album->title,
                'user_id'     => $album->user_id,
                'release_date'=> $album->release_date,
            ],

            'is_friend' => $isFriend,
        ];
    }
}