@extends('template.main')

@section('DatatablesPlugins', true)
@section('Datatables', true)
@section('DatatablesPlugins', true)

@section('subtitle', 'Livros')
@section('content_header_title', 'Livros')
@section('content_header_subtitle', 'Lista')

@section('content_body')
    @php
    $heads = [
        '#',
        'Titulo',
        'Editora',
        'Edicao',
        'Ano de Publicacao',
        ['label' => 'Ações', 'no-export' => true, 'width' => 5],
    ];

    $config = [
        'order' => [[0, 'asc']],
        'columns' => [null, null, null, null,  null, ['orderable' => false]],
    ];
    @endphp

    <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped hoverable with-buttons>
        @foreach($livros as $livro)
            <tr>
                <td>{{ $livro->Codl }}</td>
                <td>{{ $livro->Titulo }}</td>
                <td>{{ $livro->Editora }}</td>
                <td>{{ $livro->Edicao }}</td>
                <td>{{ $livro->AnoPublicacao }}</td>
                <td>
                    <button class="btn btn-xs btn-primary" title="Editar" onclick="window.location='{{ route('livro.edit', $livro->Codl) }}'">
                        <i class="fas fa-lg fa-edit"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>

    {!! $livros->links('pagination::bootstrap-5') !!}

    @if(session('success'))
        <x-adminlte-alert theme="success" title="Sucesso" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif
@endsection

