<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW Livros_View AS
            select l.Titulo,
                   group_concat(au.Nome order by au.Nome separator ', ') as Autores,
                   group_concat(a.Descricao order by a.Descricao separator ', ') as Assuntos,
                   l.Edicao, l.AnoPublicacao, l.Editora, l.Valor from Livro as l
            inner join Livro_Assunto la on l.Codl = la.Livro_Codl
            inner join Assunto a on la.Assunto_CodAs = a.CodAs
            inner join Livro_Autor al on l.Codl = al.Livro_Codl
            inner join Autor au on al.Autor_CodAu = au.CodAu
            group by l.Titulo, l.Edicao, l.AnoPublicacao, l.Editora, l.Valor
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW Livros_View');
    }
};
