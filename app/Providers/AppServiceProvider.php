<?php

namespace App\Providers;

use App\Repositories\Book\Eloquent\AuthorEloquentRepository;
use App\Repositories\Book\Eloquent\BookEloquentRepository;
use App\Repositories\Citizen\Eloquent\CitizenEloquentRepository;
use App\Repositories\Library\Eloquent\LibraryEloquentRepository;
use Core\Domain\Book\Repository\AuthorRepositoryInterface;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\Citizen\Repository\CitizenRepositoryInterface;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        CitizenRepositoryInterface::class => CitizenEloquentRepository::class,
        LibraryRepositoryInterface::class => LibraryEloquentRepository::class,
        AuthorRepositoryInterface::class => AuthorEloquentRepository::class,
        BookRepositoryInterface::class => BookEloquentRepository::class,
    ];

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
        //
    }
}
