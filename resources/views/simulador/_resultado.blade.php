@use('App\Helper\SimuladorHelper')

@php
    $totais = $resultado['totais'];
    $oportunidades = collect($resultado['oportunidades'])->sortByDesc('total_maximo')->values();
    $maiorTotal = max((float) $oportunidades->max('total_maximo'), 0.01);
    $comCorrecao = ($resultado['selic_mensal'] ?? 0) > 0;
@endphp

@if($oportunidades->isEmpty())
    <div class="text-center py-4">
        <i class="fas fa-search-dollar fa-3x text-muted mb-3"></i>
        <h4>Não identificamos oportunidades com as informações enviadas</h4>
        <p class="text-muted mb-0">
            Isso não significa que não existam créditos: a análise dos documentos fiscais pode revelar
            outras teses aplicáveis à sua empresa.
        </p>
    </div>
@else
    <div class="row text-center sim-resumo mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="sim-resumo-item sim-resumo-destaque">
                <small>Potencial estimado de recuperação</small>
                <strong>{{ SimuladorHelper::faixaMoedaHtml($totais['total_minimo'], $totais['total_maximo']) }}</strong>
                <small>últimos {{ $resultado['meses'] }} meses{{ $comCorrecao ? ', com correção SELIC' : '' }}</small>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="sim-resumo-item">
                <small>Valor principal</small>
                <strong>{{ SimuladorHelper::faixaMoedaHtml($totais['principal_minimo'], $totais['principal_maximo']) }}</strong>
                @if($comCorrecao)
                    <small>
                        + correção SELIC estimada de
                        {{ SimuladorHelper::faixaMoeda($totais['correcao_minima'], $totais['correcao_maxima']) }}
                    </small>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="sim-resumo-item">
                <small>Economia mensal daqui em diante</small>
                <strong>{{ SimuladorHelper::faixaMoedaHtml($totais['valor_mensal_minimo'], $totais['valor_mensal_maximo']) }}</strong>
                <small>ao corrigir a apuração dos tributos</small>
            </div>
        </div>
    </div>

    <h5 class="mb-3">Oportunidades identificadas ({{ $oportunidades->count() }})</h5>

    @foreach($oportunidades as $oportunidade)
        <div class="sim-oportunidade border rounded p-3 mb-3">
            <div class="d-flex flex-wrap justify-content-between">
                <div class="mr-3">
                    <h6 class="font-weight-bold mb-1">{{ $oportunidade['titulo'] }}</h6>
                    <small class="text-muted"><i class="fas fa-gavel mr-1"></i>{{ $oportunidade['fundamento'] }}</small>
                </div>
                <div class="ml-auto text-right">
                    <span class="font-weight-bold text-success">
                        {{ SimuladorHelper::faixaMoedaHtml($oportunidade['total_minimo'], $oportunidade['total_maximo']) }}
                    </span><br>
                    <small class="text-muted">em {{ $oportunidade['meses'] }} meses</small>
                </div>
            </div>

            <div class="progress my-2" style="height: 8px;"
                 title="Faixa estimada: {{ SimuladorHelper::faixaMoeda($oportunidade['total_minimo'], $oportunidade['total_maximo']) }}">
                <div class="progress-bar bg-success"
                     style="width: {{ round($oportunidade['total_minimo'] / $maiorTotal * 100, 2) }}%"></div>
                <div class="progress-bar bg-success sim-faixa"
                     style="width: {{ round(($oportunidade['total_maximo'] - $oportunidade['total_minimo']) / $maiorTotal * 100, 2) }}%"></div>
            </div>

            <p class="small mb-1">{{ $oportunidade['descricao'] }}</p>
            <small class="text-muted">
                {{ $oportunidade['base_descricao'] }}: {{ SimuladorHelper::moeda($oportunidade['base_mensal']) }}
                @if($oportunidade['base_estimada'])
                    <span class="badge badge-warning">estimado pelo faturamento</span>
                @endif
                &middot; Percentual aplicado:
                {{ SimuladorHelper::percentual($oportunidade['percentual_minimo']) }} a
                {{ SimuladorHelper::percentual($oportunidade['percentual_maximo']) }}
                &middot; Por mês: {{ SimuladorHelper::faixaMoeda($oportunidade['valor_mensal_minimo'], $oportunidade['valor_mensal_maximo']) }}
            </small>
        </div>
    @endforeach
@endif

@once
    <style>
        .sim-resumo-item {
            height: 100%; padding: 1rem; border-radius: .5rem; background: #f4f6f9;
            display: flex; flex-direction: column; justify-content: center;
        }
        .sim-resumo-item strong { font-size: 1.25rem; margin: .25rem 0; }
        .sim-resumo-item small { color: #6c757d; }
        .sim-resumo-destaque { background: #1e9e5a; color: #fff; }
        .sim-resumo-destaque small { color: rgba(255, 255, 255, .85); }
        .sim-faixa { opacity: .35; }
    </style>
@endonce
