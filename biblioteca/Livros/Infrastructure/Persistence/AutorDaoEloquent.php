<?php

namespace Biblioteca\Livros\Infrastructure\Persistence;

use Biblioteca\Livros\Domain\Entity\Autor;
use Biblioteca\Livros\Domain\Persistence\Dao\AutorDao;
use App\Models\Autor as AutorModel;

class AutorDaoEloquent implements AutorDao
{

    public function adicionar(Autor $autor): void
    {
        AutorModel::create([
            'Nome' => $autor->nome
        ]);
    }

    public function atualizar(int $id, Autor $autor): void
    {
        $autorModel = AutorModel::find($id);
        $autorModel->Nome = $autor->nome;
        $autorModel->save();
    }

    public function excluir(int $id): void
    {
        AutorModel::destroy($id);
    }
}
