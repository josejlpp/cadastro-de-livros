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
use Biblioteca\Simulador\Domain\Entity\TeseCollection;
use Biblioteca\Simulador\Domain\Persistence\Dao\SimulacaoDao;
use Biblioteca\Simulador\Domain\Persistence\Repository\SimulacaoRepository;
use Biblioteca\Simulador\Domain\Service\CalculadoraPotencial;
use Biblioteca\Simulador\Infrastructure\Persistence\SimulacaoDaoEloquent;
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
        SimulacaoDao::class => SimulacaoDaoEloquent::class,
        SimulacaoRepository::class => SimulacaoRepository::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        Event::subscribe(ReportRequestListener::class);

        $this->app->singleton(TeseCollection::class, fn () => TeseCollection::fromArray(config('simulador.teses', [])));
        $this->app->singleton(CalculadoraPotencial::class, fn ($app) => new CalculadoraPotencial(
            $app->make(TeseCollection::class),
            config('simulador.selic_mensal', 0)
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
