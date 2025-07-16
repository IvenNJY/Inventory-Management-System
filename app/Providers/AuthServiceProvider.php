<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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

        Gate::define('admin-only', function ($user) {
            \Log::info('admin-only gate check', ['user_id' => $user?->id, 'role' => $user?->role]);
            return $user && $user->role === 'admin';
        });
        Gate::define('user-only', function ($user) {
            \Log::info('user-only gate check', ['user_id' => $user?->id, 'role' => $user?->role]);
            return $user && $user->role === 'user';
        });
    }
}
