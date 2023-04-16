<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;


class AuthServiceProvider extends ServiceProvider
{
    // manage permission
    public static $permission = [
        'dashboard' => ['admin', 'user'],
        'index-user' => ['admin'],
    ];
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
    public function boot()
    {
        //
        $this->registerPolicies();

        Gate::before(function (User $user) {
            if ($user->role == 'superadmin') {
                return true;
            }
        });

        foreach (self::$permission as $action => $roles) {
            Gate::define($action, function (User $user) use ($roles) {
                if (in_array($user->role, $roles)){
                    return true;
                }
            });
        }
    }
}
