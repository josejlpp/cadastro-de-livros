@extends('template.main')

@use('App\Helper\SimuladorHelper')

@section('plugins.Datatables', true)

@section('subtitle', 'Simulações')
@section('content_header_title', 'Simulações')
@section('content_header_subtitle', 'Lista')

@section('content_body')
    @php
    $heads = [
        '#',
        'Data',
        'Empresa',
        'Contato',
        'Regime',
        'Faturamento mensal',
        'Potencial estimado',
        ['label' => 'Ações', 'no-export' => true, 'width' => 5],
    ];

    $config = [
        'order' => [[0, 'desc']],
        'paging' => false,
        'info' => false,
        'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
    ];
    @endphp

    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped hoverable>
        @foreach($simulacoes as $simulacao)
            <tr>
                <td>{{ $simulacao->id }}</td>
                <td>{{ $simulacao->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    {{ $simulacao->empresa }}
                    @if($simulacao->cnpj)
                        <br><small class="text-muted">{{ SimuladorHelper::cnpj($simulacao->cnpj) }}</small>
                    @endif
                </td>
                <td>
                    {{ $simulacao->nome }}<br>
                    <small class="text-muted">{{ $simulacao->email }} &middot; {{ $simulacao->telefone }}</small>
                </td>
                <td>{{ $simulacao->regime->label() }}</td>
                <td>{{ SimuladorHelper::moeda($simulacao->faturamento_mensal) }}</td>
                <td>{{ SimuladorHelper::faixaMoeda($simulacao->total_minimo, $simulacao->total_maximo) }}</td>
                <td class="text-nowrap">
                    <button class="btn btn-xs btn-primary" title="Ver" onclick="window.location='{{ route('simulacao.show', $simulacao->id) }}'">
                        <i class="fas fa-lg fa-eye"></i>
                    </button>
                    <form action="{{ route('simulacao.destroy', $simulacao->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Excluir esta simulação?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-xs btn-danger" title="Excluir">
                            <i class="fas fa-lg fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>

    {!! $simulacoes->links('pagination::bootstrap-5') !!}

    @if(session('success'))
        <x-adminlte-alert theme="success" title="Sucesso" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif
@endsection
