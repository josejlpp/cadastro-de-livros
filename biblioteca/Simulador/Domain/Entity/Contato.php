<?php

namespace Biblioteca\Simulador\Domain\Entity;

use DomainException;

readonly class Contato
{
    public string|null $cnpj;

    public function __construct(
        public string $nome,
        public string $email,
        public string $telefone,
        public string $empresa,
        string|null   $cnpj = null
    ) {
        $this->cnpj = $cnpj ? self::normalizarCnpj($cnpj) : null;
        $this->validate();
    }

    private function validate(): void
    {
        if (empty(trim($this->nome))) {
            throw new DomainException('Nome do contato não pode ser vazio');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException('E-mail do contato é inválido');
        }

        $digitosTelefone = strlen(preg_replace('/\D/', '', $this->telefone));
        if ($digitosTelefone < 10 || $digitosTelefone > 11) {
            throw new DomainException('Telefone do contato deve ter DDD e 8 ou 9 dígitos');
        }

        if (empty(trim($this->empresa))) {
            throw new DomainException('Nome da empresa não pode ser vazio');
        }

        if ($this->cnpj !== null && !self::cnpjValido($this->cnpj)) {
            throw new DomainException('CNPJ informado é inválido');
        }
    }

    public static function normalizarCnpj(string $cnpj): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $cnpj));
    }

    /**
     * Aceita o CNPJ numérico e o alfanumérico (IN RFB 2.229/2024): cada caractere
     * vale o seu código ASCII menos 48 no cálculo dos dígitos verificadores.
     */
    public static function cnpjValido(string $cnpj): bool
    {
        $cnpj = self::normalizarCnpj($cnpj);

        if (!preg_match('/^[A-Z0-9]{12}\d{2}$/', $cnpj) || preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        foreach ([12, 13] as $posicao) {
            $soma = 0;
            $peso = $posicao - 7;
            for ($i = 0; $i < $posicao; $i++) {
                $soma += (ord($cnpj[$i]) - 48) * $peso--;
                if ($peso < 2) {
                    $peso = 9;
                }
            }

            $digito = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
            if ((int) $cnpj[$posicao] !== $digito) {
                return false;
            }
        }

        return true;
    }
}
