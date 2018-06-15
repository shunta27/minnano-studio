<?php

namespace App\Providers;

use App\Providers\AuthUserProvider;
use App\Repositories\Eloquent\Models\UserInformation;
use App\Repositories\Eloquent\Models\Studio;
use App\Repositories\Eloquent\Models\Comment;
use App\Policies\UserInformationPolicy;
use App\Policies\StudioPolicy;
use App\Policies\CommentPolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        UserInformation::class => UserInformationPolicy::class,
        Studio::class => StudioPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    public function register()
    {
        parent::register();

        Auth::provider('auth_ex', function ($app, array $config) {
            $model = $app['config']['auth.providers.users.model'];
			return new AuthUserProvider($app['hash'], $model);
        });
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::$revokeOtherTokens;
        Passport::$pruneRevokedTokens;

        Gate::define('system-only', function ($user) {
            return $user->isSystem();
        });
        Gate::define('admin-higher', function ($user) {
            return $user->isAdmin();
        });
        Gate::define('user-higher', function ($user) {
            return $user->isUser();
        });
    }
}
