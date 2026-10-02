@extends('template.main')

@use('App\Helper\SimuladorHelper')

@section('subtitle', 'Simulação #' . $simulacao->id)
@section('content_header_title', 'Simulações')
@section('content_header_subtitle', $simulacao->empresa)

@section('content_body')
    <div class="row">
        <div class="col-md-4">
            <x-adminlte-card title="Contato" theme="primary" icon="fas fa-user">
                <dl class="mb-0">
                    <dt>Nome</dt>
                    <dd>{{ $simulacao->nome }}</dd>
                    <dt>Empresa</dt>
                    <dd>{{ $simulacao->empresa }}</dd>
                    @if($simulacao->cnpj)
                        <dt>CNPJ</dt>
                        <dd>{{ SimuladorHelper::cnpj($simulacao->cnpj) }}</dd>
                    @endif
                    <dt>E-mail</dt>
                    <dd><a href="mailto:{{ $simulacao->email }}">{{ $simulacao->email }}</a></dd>
                    <dt>Telefone</dt>
                    <dd>{{ $simulacao->telefone }}</dd>
                    <dt>Data da simulação</dt>
                    <dd class="mb-0">{{ $simulacao->created_at->format('d/m/Y H:i') }}</dd>
                </dl>
            </x-adminlte-card>

            <x-adminlte-card title="Informações declaradas" theme="secondary" icon="fas fa-building">
                <dl class="mb-0">
                    <dt>Regime tributário</dt>
                    <dd>{{ $simulacao->regime->label() }}</dd>
                    <dt>Segmento</dt>
                    <dd>{{ $simulacao->segmento->label() }}</dd>
                    <dt>Faturamento mensal</dt>
                    <dd>{{ SimuladorHelper::moeda($simulacao->faturamento_mensal) }}</dd>
                    @foreach([
                        'folha_pagamento_mensal' => 'Folha de pagamento mensal',
                        'energia_mensal' => 'Energia elétrica mensal',
                        'combustivel_mensal' => 'Combustíveis mensal',
                        'icms_mensal' => 'ICMS mensal',
                        'iss_mensal' => 'ISS mensal',
                    ] as $campo => $label)
                        @if($simulacao->$campo > 0)
                            <dt>{{ $label }}</dt>
                            <dd>{{ SimuladorHelper::moeda($simulacao->$campo) }}</dd>
                        @endif
                    @endforeach
                    @if($simulacao->percentual_monofasico > 0)
                        <dt>Receita com produtos monofásicos</dt>
                        <dd>{{ SimuladorHelper::percentual($simulacao->percentual_monofasico / 100) }}</dd>
                    @endif
                    <dt>Período analisado</dt>
                    <dd class="mb-0">{{ $simulacao->meses }} meses</dd>
                </dl>
            </x-adminlte-card>
        </div>

        <div class="col-md-8">
            <x-adminlte-card title="Resultado" theme="success" icon="fas fa-chart-line">
                @include('simulador._resultado', ['resultado' => $simulacao->resultado])

                <a href="{{ route('simulador.resultado', $simulacao->uuid) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt"></i> Página de resultado enviada ao contato
                </a>
            </x-adminlte-card>
        </div>
    </div>

    <a href="{{ route('simulacao.index') }}" class="btn btn-default">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
@endsection
