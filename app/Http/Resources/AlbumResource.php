<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    public function toArray($request): array
    {
        $owner   = $this->user;
        $current = $request->user();

        $isFriend = $current
            ? $current->friends()->where('friend_id', $owner->id)->exists()
            : false;

        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'release_date' => $this->release_date,
            'image_url'    => $this->image_path
                ? url('storage/'.$this->image_path)
                : null,
            'user' => [
                'id'   => $owner->id,
                'name' => $owner->name,
            ],
            'is_friend'  => $isFriend,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}