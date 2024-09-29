<?php

namespace Biblioteca\Livros\Infrastructure\Persistence;

use Biblioteca\Livros\Domain\Entity\Livro;
use Biblioteca\Livros\Domain\Persistence\Dao\LivroDao;
use App\Models\Livro as LivroModel;

class LivroDaoEloquent implements LivroDao
{

    public function adicionar(Livro $livro): void
    {
        $livroModel = LivroModel::create([
            'titulo' => $livro->titulo,
            'editora' => $livro->editora,
            'edicao' => $livro->edicao,
            'ano' => $livro->anoPublicacao
        ]);

        $livroModel->autores()->attach($livro->getAutores()->map(fn($autor) => $autor->CodAu));
        $livroModel->assuntos()->attach($livro->getAssuntos()->map(fn($assunto) => $assunto->CodAs));
    }

    public function atualizar(int $id, Livro $livro): void
    {
        $livroModel = LivroModel::find($id);
        $livroModel->titulo = $livro->titulo;
        $livroModel->editora = $livro->editora;
        $livroModel->edicao = $livro->edicao;
        $livroModel->ano = $livro->anoPublicacao;
        $livroModel->autores()->sync($livro->getAutores()->map(fn($autor) => $autor->CodAu));
        $livroModel->assuntos()->sync($livro->getAssuntos()->map(fn($assunto) => $assunto->CodAs));
        $livroModel->save();
    }

    public function excluir(int $id): void
    {
        $livroModel = LivroModel::find($id);
        $livroModel->autores()->detach();
        $livroModel->assuntos()->detach();
        $livroModel->delete();
    }
}
