@extends('template.main')

@section('DatatablesPlugins', true)
@section('Datatables', true)
@section('DatatablesPlugins', true)

@section('subtitle', 'Assuntos')
@section('content_header_title', 'Assuntos')
@section('content_header_subtitle', 'Lista')

@section('content_body')
    @php
    $heads = [
        '#',
        'Descrição',
        ['label' => 'Ações', 'no-export' => true, 'width' => 5],
    ];

    $config = [
        'order' => [[0, 'asc']],
        'columns' => [null, null, null, ['orderable' => false]],
    ];
    @endphp

    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped hoverable with-buttons>
        @foreach($assuntos as $assunto)
            <tr>
                <td>{{ $assunto->CodAs }}</td>
                <td>{{ $assunto->Descricao }}</td>
                <td>
                    <button class="btn btn-xs btn-primary" title="Editar" onclick="window.location='{{ route('assunto.edit', $assunto->CodAs) }}'">
                        <i class="fas fa-lg fa-edit"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>

    {!! $assuntos->links('pagination::bootstrap-5') !!}

    @if(session('success'))
        <x-adminlte-alert theme="success" title="Sucesso" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif
@endsection

