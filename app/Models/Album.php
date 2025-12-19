<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class Album extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'release_date',
        'image_path',
        'user_id',
    ];

    protected static function booted()
    {
        static::deleting(function (Album $album) {
            $user = Auth::user();

            // Если нет пользователя (например, вызывают из консоли) — просто разрешаем.
            if (!$user) {
                return true;
            }

            // Запрещаем удаление, если это не владелец и не админ.
            if (!$user->is_admin && $album->user_id !== $user->id) {
                return false; // отменяет операцию delete()
            }

            return true;
        });
    }

    // Мутаторы/аксессоры для даты (расширенный уровень)
    // Храним в БД как Y-m-d, наружу отдаём как d.m.Y
    public function getReleaseDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d.m.Y') : null;
    }

    public function setReleaseDateAttribute($value)
    {
        if (!$value) {
            $this->attributes['release_date'] = null;
            return;
        }

        // ожидаем ввод в формате дд.мм.гггг
        $this->attributes['release_date'] =
            Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}