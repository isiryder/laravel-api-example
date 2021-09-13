<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\EloquentRepositoryInterface; 
use App\Repository\AuthorRepositoryInterface; 
use App\Repository\BookRepositoryInterface; 
use App\Repository\LibraryRepositoryInterface; 
use App\Repositories\Eloquent\AuthorRepository; 
use App\Repositories\Eloquent\BookRepository; 
use App\Repositories\Eloquent\LibraryRepository; 
use App\Repositories\Eloquent\BaseRepository; 

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\EloquentRepositoryInterface::class, \App\Repositories\Eloquent\BaseRepository::class);
        $this->app->bind(\App\Repositories\AuthorRepositoryInterface::class, \App\Repositories\Eloquent\AuthorRepository::class);
        $this->app->bind(\App\Repositories\BookRepositoryInterface::class, \App\Repositories\Eloquent\BookRepository::class);
        $this->app->bind(\App\Repositories\LibraryRepositoryInterface::class, \App\Repositories\Eloquent\LibraryRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
