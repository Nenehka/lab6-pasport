<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Album;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function getRouteKeyName(): string
    {
        return 'name';   // будет искать пользователя по полю name
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Кого я добавил в друзья
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends_users', 'user_id', 'friend_id')
                    ->withTimestamps();
    }

    // Кто добавил меня в друзья
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends_users', 'friend_id', 'user_id')
                    ->withTimestamps();
    }

    // Удобный метод-помощник
    public function isFriendsWith(User $user): bool
    {
        return $this->friends->contains($user->id);
    }
}
