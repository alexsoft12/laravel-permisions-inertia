<?php

namespace App\Providers;

use Auth;
use Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Implicitly grant "Super Admin" role all permission checks using can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

            Inertia::share([
                'authUser' => function () {
                    return [
                        'roles' => Auth::user() != null ? Auth::user()->roles()->pluck('name'): ['aaa'],
                        'permissions' => Auth::user() != null ? Auth::user()->getPermissionsViaRoles()->pluck('name') : ['bbb'],
                    ];
                }
            ]);

    }
}
