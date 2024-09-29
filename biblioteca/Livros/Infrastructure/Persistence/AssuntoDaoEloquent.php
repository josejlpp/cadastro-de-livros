<?php

namespace Biblioteca\Livros\Infrastructure\Persistence;

use Biblioteca\Livros\Domain\Entity\Assunto;
use Biblioteca\Livros\Domain\Persistence\Dao\AssuntoDao;
use App\Models\Assunto as AssuntoModel;

class AssuntoDaoEloquent implements AssuntoDao
{

    public function adicionar(Assunto $assunto): void
    {
        AssuntoModel::create([
            'Descricao' => $assunto->descricao
        ]);
    }

    public function atualizar(int $id, Assunto $assunto): void
    {
        $assuntoModel = AssuntoModel::find($id);
        $assuntoModel->Descricao = $assunto->descricao;
        $assuntoModel->save();
    }

    public function excluir(int $id): void
    {
        AssuntoModel::destroy($id);
    }
}
