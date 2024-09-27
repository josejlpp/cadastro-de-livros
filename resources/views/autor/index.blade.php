@extends('template.main')

@section('DatatablesPlugins', true)
@section('Datatables', true)
@section('DatatablesPlugins', true)

@section('subtitle', 'Autores')
@section('content_header_title', 'Autores')
@section('content_header_subtitle', 'Lista')

@section('content_body')
    @php
    $heads = [
        '#',
        'Nome',
        ['label' => 'Ações', 'no-export' => true, 'width' => 5],
    ];

    $config = [
        'order' => [[0, 'asc']],
        'columns' => [null, null, ['orderable' => false]],
    ];
    @endphp

    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped hoverable with-buttons>
        @foreach($autores as $autor)
            <tr>
                <td>{{ $autor->CodAu }}</td>
                <td>{{ $autor->Nome }}</td>
                <td>
                    <button class="btn btn-xs btn-primary" title="Editar" onclick="window.location='{{ route('autor.edit', $autor->CodAu) }}'">
                        <i class="fas fa-lg fa-edit"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>

    {!! $autores->links('pagination::bootstrap-5') !!}

    @if(session('success'))
        <x-adminlte-alert theme="success" title="Sucesso" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif
@endsection

