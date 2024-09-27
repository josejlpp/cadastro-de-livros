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
        Livro::factory(10)
            ->has(Assunto::factory()->count(2), 'assuntos')
            ->has(Autor::factory()->count(2), 'autores')
            ->create();

        Livro::factory(15)
            ->has(Assunto::factory()->count(2), 'assuntos')
            ->has(Autor::factory()->count(1), 'autores')
            ->create();

        Livro::factory(5)
            ->has(Assunto::factory()->count(1), 'assuntos')
            ->has(Autor::factory()->count(4), 'autores')
            ->create();

        Livro::factory(5)
            ->has(Assunto::factory()->count(1), 'assuntos')
            ->has(Autor::factory()->count(1), 'autores')
            ->create();
    }
}
