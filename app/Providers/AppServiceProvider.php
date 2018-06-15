<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // User
        $this->app->bind(
            \App\Repositories\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
        // UserInformation
        $this->app->bind(
            \App\Repositories\UserInformationRepositoryInterface::class,
            \App\Repositories\Eloquent\UserInformationRepository::class
        );
        // Studio
        $this->app->bind(
            \App\Repositories\StudioRepositoryInterface::class,
            \App\Repositories\Eloquent\StudioRepository::class
        );
        // Comment
        $this->app->bind(
            \App\Repositories\CommentRepositoryInterface::class,
            \App\Repositories\Eloquent\CommentRepository::class
        );
    }
}
