<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use App\Models\Album;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Passport::routes();

        // Может редактировать альбом: владелец или админ
        Gate::define('update-album', function (User $user, Album $album) {
            return $user->is_admin || $album->user_id === $user->id;
        });

        // Может мягко удалить альбом: владелец или админ
        Gate::define('delete-album', function (User $user, Album $album) {
            return $user->is_admin || $album->user_id === $user->id;
        });

        // Восстановить и удалить навсегда может только админ
        Gate::define('restore-album', function (User $user, Album $album) {
            return $user->is_admin;
        });

        Gate::define('force-delete-album', function (User $user, Album $album) {
            return $user->is_admin;
        });
    }
}