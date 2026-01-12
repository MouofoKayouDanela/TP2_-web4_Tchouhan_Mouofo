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
            \App\Repository\RepositoryInterface::class,
            \App\Repository\Eloquent\BaseRepository::class
        );
       
       
        $this->app->bind(
            \App\Repository\FilmRepositoryInterface::class,
            \App\Repository\Eloquent\FilmRepository::class
        );


        $this->app->bind(
            \App\Repository\CriticRepositoryInterface::class,
            \App\Repository\Eloquent\CriticRepository::class
        );

        
        $this->app->bind(
            \App\Repository\UserRepositoryInterface::class,
            \App\Repository\Eloquent\UserRepository::class
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
