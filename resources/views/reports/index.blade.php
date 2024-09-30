@extends('template.main')

@section('Datatables', true)

@section('subtitle', 'Relatórios')
@section('content_header_title', 'Relatórios')
@section('content_header_subtitle', 'Lista')

@section('content_body')
    @php
        $heads = [
            '#',
            'Data solicitação',
            'Status',
            'Ação',
        ];

        $config = [
            'order' => [[2, 'asc']],
        ];
    @endphp

    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped hoverable with-buttons beautify>
        @forelse($reports as $report)
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->created_at->format('d/m/Y H:i:s') }}</td>
                <td>{{ __($report->status) }}</td>
                <td>
                    @if($report->status == 'Completo' || $report->status == 'Falhou!')
                        @if($report->status == 'Completo')
                            <a href="{{ route('report.download', $report->file_name) }}" target="_blank" class="btn btn-xs btn-primary">Visualizar</a>
                        @endif
                        <form action="{{ route('report.delete', $report) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-danger">Excluir</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Nenhum relatório encontrado</td>
            </tr>
        @endforelse
    </x-adminlte-datatable>

    <a href="{{ route('report.create') }}" class="btn btn-md btn-primary">Gerar novo relatório</a>

    {!! $reports->links('pagination::bootstrap-5') !!}

    @if(session('success'))
        <br>
        <x-adminlte-alert theme="success" title="Sucesso" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    @if(session('error'))
        <br>
        <x-adminlte-alert theme="danger" title="Erro" dismissable>
            {{ session('error') }}
        </x-adminlte-alert>
    @endif
@endsection

