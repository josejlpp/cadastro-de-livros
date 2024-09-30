<?php

namespace Biblioteca\Livros\Infrastructure\Persistence;

use Biblioteca\Livros\Domain\Entity\AssuntoCollection;
use Biblioteca\Livros\Domain\Entity\AutorCollection;
use Biblioteca\Livros\Domain\Entity\Livro;
use Biblioteca\Livros\Domain\Persistence\Dao\LivroDao;
use App\Models\Livro as LivroModel;

class LivroDaoEloquent implements LivroDao
{

    public function adicionar(Livro $livro): void
    {
        $livroModel = LivroModel::create([
            'Titulo' => $livro->titulo,
            'Editora' => $livro->editora,
            'Edicao' => $livro->edicao,
            'AnoPublicacao' => $livro->anoPublicacao,
            'Valor' => $livro->valor,
        ]);

        $this->syncAutores($livroModel, $livro->getAutores());
        $this->syncAssuntos($livroModel, $livro->getAssuntos());
    }

    public function atualizar(int $id, Livro $livro): void
    {
        $livroModel = LivroModel::find($id);
        $livroModel->Titulo = $livro->titulo;
        $livroModel->Editora = $livro->editora;
        $livroModel->Edicao = $livro->edicao;
        $livroModel->AnoPublicacao = $livro->anoPublicacao;
        $livroModel->Valor = $livro->valor;
        $livroModel->save();

        $this->syncAutores($livroModel, $livro->getAutores());
        $this->syncAssuntos($livroModel, $livro->getAssuntos());
    }

    private function syncAutores(LivroModel &$livroModel, AutorCollection $autores)
    {
        $autoresIds = [];
        foreach ($autores as $autor) {
            $autoresIds[] = $autor->CodAu;
        }

        $livroModel->autores()->sync($autoresIds);
    }

    private function syncAssuntos(LivroModel &$livroModel, AssuntoCollection $assuntos)
    {
        $assuntosIds = [];
        foreach ($assuntos as $assunto) {
            $assuntosIds[] = $assunto->CodAs;
        }

        $livroModel->assuntos()->sync($assuntosIds);
    }

    public function excluir(int $id): void
    {
        $livroModel = LivroModel::find($id);
        $livroModel->autores()->detach();
        $livroModel->assuntos()->detach();
        $livroModel->delete();
    }
}
