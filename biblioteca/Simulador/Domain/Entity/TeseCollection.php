<?php

namespace Biblioteca\Simulador\Domain\Entity;

use ArrayIterator;
use Biblioteca\Simulador\Domain\Enum\BaseCalculo;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use Countable;
use DomainException;
use IteratorAggregate;
use Traversable;

class TeseCollection implements Countable, IteratorAggregate
{
    private array $teses = [];

    /**
     * Monta as teses a partir do array de configuração (config/simulador.php).
     */
    public static function fromArray(array $config): self
    {
        $collection = new self();

        foreach ($config as $chave => $tese) {
            $base = BaseCalculo::tryFrom($tese['base'] ?? '');
            if ($base === null) {
                throw new DomainException("Tese {$chave} possui base de cálculo inválida");
            }

            $segmentos = [];
            foreach ($tese['segmentos'] ?? [] as $segmento) {
                $segmentos[] = Segmento::tryFrom($segmento)
                    ?? throw new DomainException("Tese {$chave} possui segmento inválido: {$segmento}");
            }

            $collection->adicionar(new Tese(
                chave: $chave,
                titulo: $tese['titulo'] ?? '',
                descricao: $tese['descricao'] ?? '',
                fundamento: $tese['fundamento'] ?? '',
                base: $base,
                percentuais: $tese['percentuais'] ?? [],
                segmentos: $segmentos,
                fatorEstimativaBase: $tese['base_estimada_sobre_faturamento'] ?? null,
                mesesMaximo: $tese['meses_maximo'] ?? null
            ));
        }

        return $collection;
    }

    public function adicionar(Tese $tese): void
    {
        if (isset($this->teses[$tese->chave])) {
            throw new DomainException("Tese {$tese->chave} já adicionada");
        }

        $this->teses[$tese->chave] = $tese;
    }

    /**
     * Para cada base de cálculo, os perfis ("regime:segmento") em que alguma tese a utiliza.
     * Permite ao formulário pedir apenas as informações relevantes para o perfil escolhido.
     *
     * @return array<string, string[]>
     */
    public function perfisPorBase(): array
    {
        $perfis = [];

        foreach ($this->teses as $tese) {
            $segmentos = $tese->segmentos ?: Segmento::cases();
            foreach (array_keys($tese->percentuais) as $regime) {
                foreach ($segmentos as $segmento) {
                    $perfis[$tese->base->value][] = "{$regime}:{$segmento->value}";
                }
            }
        }

        return array_map(fn (array $lista) => array_values(array_unique($lista)), $perfis);
    }

    public function count(): int
    {
        return count($this->teses);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->teses);
    }
}
