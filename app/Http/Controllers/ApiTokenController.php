<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function show(Request $request)
    {
        $user  = $request->user();
        $token = $user->tokens()->latest()->first(); // последний токен пользователя

        // новый токен (после генерации) будем показывать через сессию
        $newToken = session('new_token');

        return view('profile.api_token', compact('user', 'token', 'newToken'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // при желании можно удалить старые токены
        $user->tokens()->delete();

        $result = $user->createToken('Rammstein API Token');
        $plainTextToken = $result->accessToken;

        return redirect()
            ->route('profile.api-token')
            ->with('new_token', $plainTextToken);
    }
}