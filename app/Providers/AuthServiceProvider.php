<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

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
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-profile', function ($user, User $profile) {
            // if public or the profile is my one or i follow this profile and he accepted it then allow the user to see it
            if ($profile->status == 'public' || $user->id == $profile->id || $user->followingAndAccepted($profile)) {
                return true;
            } else {
                return false;
            }
        });
    }
}
