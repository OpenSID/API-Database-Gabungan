<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Services\Auth\OpenKabAuthGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        $this->bootExtendGuard();
        $this->registerPolicies();
    }

    protected function bootExtendGuard()
    {
        $this->app['auth']->extend('openkab', function ($app, $name, array $config) {
            return new OpenKabAuthGuard($app->make('request'));
        });

        Gate::before(function ($user, $ability) {
            if ($user && isset($user->abilities)) {
                return in_array('*', $user->abilities) || array_key_exists($ability, array_flip($user->abilities));
            }
        });
    }
}
