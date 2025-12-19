<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('friends_users', function (Blueprint $table) {
            $table->id();

            // пользователь, который "дружит"
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // тот, кого он добавил
            $table->foreignId('friend_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();

            // не даём создать дубликат этой пары
            $table->unique(['user_id', 'friend_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('friends_users');
    }
};