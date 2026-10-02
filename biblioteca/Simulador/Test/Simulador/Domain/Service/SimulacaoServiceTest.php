<?php

namespace Biblioteca\Simulador\Test\Simulador\Domain\Service;

use Biblioteca\Simulador\Domain\Dto\SimulacaoDto;
use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use Biblioteca\Simulador\Domain\Service\SimulacaoService;
use DomainException;
use PHPUnit\Framework\TestCase;

class SimulacaoServiceTest extends TestCase
{
    private function dto(string $regime = 'lucro_real', string $segmento = 'industria'): SimulacaoDto
    {
        return new SimulacaoDto(
            nome: 'Maria Souza',
            email: 'maria@example.com',
            telefone: '(85) 99999-8888',
            empresa: 'Metalúrgica Exemplo',
            cnpj: '11.222.333/0001-81',
            regime: $regime,
            segmento: $segmento,
            faturamentoMensal: 1000000,
            folhaPagamentoMensal: 150000,
            meses: 36
        );
    }

    public function testCriarPerfilTributarioAPartirDoDto()
    {
        $perfil = (new SimulacaoService())->buildPerfilTributarioFromDto($this->dto());

        $this->assertSame(RegimeTributario::LucroReal, $perfil->regime);
        $this->assertSame(Segmento::Industria, $perfil->segmento);
        $this->assertEquals(1000000, $perfil->faturamentoMensal);
        $this->assertEquals(150000, $perfil->folhaPagamentoMensal);
        $this->assertEquals(36, $perfil->meses);
    }

    public function testCriarContatoAPartirDoDto()
    {
        $contato = (new SimulacaoService())->buildContatoFromDto($this->dto());

        $this->assertEquals('Maria Souza', $contato->nome);
        $this->assertEquals('11222333000181', $contato->cnpj);
    }

    public function testRegimeInvalido()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Regime tributário inválido');

        (new SimulacaoService())->buildPerfilTributarioFromDto($this->dto(regime: 'mei'));
    }

    public function testSegmentoInvalido()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Segmento de atuação inválido');

        (new SimulacaoService())->buildPerfilTributarioFromDto($this->dto(segmento: 'mineracao'));
    }
}
