<?php

namespace App\Providers;

use App\Listeners\ReportRequestListener;
use Biblioteca\Livros\Domain\Persistence\Dao\AssuntoDao;
use Biblioteca\Livros\Domain\Persistence\Dao\AutorDao;
use Biblioteca\Livros\Domain\Persistence\Dao\LivroDao;
use Biblioteca\Livros\Domain\Persistence\Repository\AssuntoRepository;
use Biblioteca\Livros\Domain\Persistence\Repository\AutorRepository;
use Biblioteca\Livros\Domain\Persistence\Repository\LivroRepository;
use Biblioteca\Livros\Infrastructure\Persistence\AssuntoDaoEloquent;
use Biblioteca\Livros\Infrastructure\Persistence\AutorDaoEloquent;
use Biblioteca\Livros\Infrastructure\Persistence\LivroDaoEloquent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public $bindings = [
        LivroDao::class => LivroDaoEloquent::class,
        AutorDao::class => AutorDaoEloquent::class,
        AssuntoDao::class => AssuntoDaoEloquent::class,
        LivroRepository::class => LivroRepository::class,
        AutorRepository::class => AutorRepository::class,
        AssuntoRepository::class => AssuntoRepository::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        Event::subscribe(ReportRequestListener::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
