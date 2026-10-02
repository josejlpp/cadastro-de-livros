<?php

namespace Biblioteca\Simulador\Domain\Entity;

class Simulacao
{
    public readonly int|null $id;
    public readonly string|null $uuid;

    public function __construct(
        public readonly Contato            $contato,
        public readonly PerfilTributario   $perfil,
        public readonly ResultadoSimulacao $resultado
    ) {
    }

    public function adicionarIdentificacao(int $id, string $uuid): void
    {
        $this->id = $id;
        $this->uuid = $uuid;
    }
}
