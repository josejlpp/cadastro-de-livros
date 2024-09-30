<?php

namespace Database\Seeders;

use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $assuntos = Assunto::factory()->count(10)->create();
        $autores = Autor::factory()->count(5)->create();

        // 2 autores e 2 assuntos
        $livros = Livro::factory(5)->create();

        $livros->each(function ($livro) use ($autores) {
            $livro->autores()->attach(
                $autores->random(2)->pluck('CodAu')->toArray()
            );
        });

        $livros->each(function ($livro) use ($assuntos) {
            $livro->assuntos()->attach(
                $assuntos->random(2)->pluck('CodAs')->toArray()
            );
        });

        // 1 autores e 2 assunto
        $livros = Livro::factory(15)->create();

        $livros->each(function ($livro) use ($autores) {
            $livro->autores()->attach(
                $autores->random(1)->pluck('CodAu')->toArray()
            );
        });

        $livros->each(function ($livro) use ($assuntos) {
            $livro->assuntos()->attach(
                $assuntos->random(2)->pluck('CodAs')->toArray()
            );
        });


        $livros->each(function ($livro) use ($assuntos) {
            $livro->assuntos()->attach(
                $assuntos->random(1)->pluck('CodAs')->toArray()
            );
        });

        // 1 autor e 1 assunto
        $livros = Livro::factory(20)->create();

        $livros->each(function ($livro) use ($autores) {
            $livro->autores()->attach(
                $autores->random(1)->pluck('CodAu')->toArray()
            );
        });

        $livros->each(function ($livro) use ($assuntos) {
            $livro->assuntos()->attach(
                $assuntos->random(1)->pluck('CodAs')->toArray()
            );
        });
    }
}
