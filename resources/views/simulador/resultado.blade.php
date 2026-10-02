@extends('simulador.layout')

@use('App\Helper\SimuladorHelper')

@section('title', 'Resultado da simulação')

@section('cabecalho')
    <h1>{{ strtok($simulacao->nome, ' ') }}, este é o potencial estimado da {{ $simulacao->empresa }}</h1>
    <p class="lead mb-0">
        {{ $simulacao->regime->label() }} &middot; {{ $simulacao->segmento->label() }} &middot;
        faturamento mensal de {{ SimuladorHelper::moeda($simulacao->faturamento_mensal) }}
    </p>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card sim-card">
                <div class="card-body p-4 p-md-5">
                    @include('simulador._resultado', ['resultado' => $simulacao->resultado])

                    <div class="alert alert-light border small mt-4 mb-0">
                        <i class="fas fa-info-circle mr-1"></i> {{ config('simulador.aviso_legal') }}
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 no-print">
                @if($whatsapp = SimuladorHelper::linkWhatsapp($simulacao->empresa))
                    <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="btn btn-success btn-lg mb-2">
                        <i class="fab fa-whatsapp mr-1"></i> Falar com um especialista
                    </a>
                @endif
                <button type="button" class="btn btn-outline-primary btn-lg mb-2" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> Imprimir / salvar PDF
                </button>
                <a href="{{ route('simulador.index') }}" class="btn btn-link btn-lg mb-2">Nova simulação</a>
            </div>
        </div>
    </div>
@endsection
