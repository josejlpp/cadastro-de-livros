<?php

namespace App\Providers;

use Biblioteca\Livros\Domain\Persistence\Dao\AssuntoDao;
use Biblioteca\Livros\Domain\Persistence\Dao\AutorDao;
use Biblioteca\Livros\Domain\Persistence\Dao\LivroDao;
use Biblioteca\Livros\Domain\Persistence\Repository\AssuntoRepository;
use Biblioteca\Livros\Domain\Persistence\Repository\AutorRepository;
use Biblioteca\Livros\Domain\Persistence\Repository\LivroRepository;
use Biblioteca\Livros\Infrastructure\Persistence\AssuntoDaoEloquent;
use Biblioteca\Livros\Infrastructure\Persistence\AutorDaoEloquent;
use Biblioteca\Livros\Infrastructure\Persistence\LivroDaoEloquent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(
            LivroDao::class,
            LivroDaoEloquent::class
        );

        $this->app->bind(
            AutorDao::class,
            AutorDaoEloquent::class
        );

        $this->app->bind(
            AssuntoDao::class,
            AssuntoDaoEloquent::class
        );

        $this->app->bind(
            AssuntoRepository::class,
            AssuntoRepository::class
        );

        $this->app->bind(
            AutorRepository::class,
            AutorRepository::class
        );

        $this->app->bind(
            LivroRepository::class,
            LivroRepository::class
        );
    }
}
