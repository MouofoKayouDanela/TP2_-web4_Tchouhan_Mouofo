<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\RoleRepositoryInterface::class,
            \App\Repositories\Eloquent\RoleRepository::class
        );

        $this->app->bind(
            \App\Repository\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );

         $this->app->bind(
            \App\Repositories\Interfaces\LanguageRepositoryInterface::class,
            \App\Repositories\Eloquent\LanguageRepository::class
        );


         $this->app->bind(
            \App\Repositories\Interfaces\FilmRepositoryInterface::class,
            \App\Repositories\Eloquent\FilmRepository::class
        );

         $this->app->bind(
            \App\Repositories\Interfaces\RoleRepositoryInterface::class,
            \App\Repositories\Eloquent\RoleRepository::class
        );

           $this->app->bind(
            \App\Repositories\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
