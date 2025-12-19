<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\FriendAdded;

class FriendController extends Controller
{
    // Добавить пользователя в друзья
    public function store(User $user)
    {
        $current = Auth::user();

        // Нельзя добавить самого себя
        if ($current->id === $user->id) {
            return back()->with('error', 'Нельзя добавить в друзья самого себя.');
        }

        // Добавляем связь user -> friend
        $current->friends()->syncWithoutDetaching([$user->id]);

        event(new FriendAdded($current, $user));

        // (Расширенный уровень) Автоматическая обратная дружба:
        // $user->friends()->syncWithoutDetaching([$current->id]);

        return back()->with('success', 'Пользователь добавлен в друзья.');
    }

    // Удалить из друзей
    public function destroy(User $user)
    {
        $current = Auth::user();

        if ($current->id === $user->id) {
            return back()->with('error', 'Нельзя удалить себя из друзей.');
        }

        // Удаляем связь user -> friend
        $current->friends()->detach($user->id);

        // (Расширенный уровень) При желании можно удалять и обратную связь:
        // $user->friends()->detach($current->id);

        return back()->with('success', 'Пользователь удалён из друзей.');
    }
}