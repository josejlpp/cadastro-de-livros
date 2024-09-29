@extends('template.main')

@section('subtitle', 'Autor - Cadastro')
@section('content_header_title', 'Cadastro')
@section('content_header_subtitle', 'Autor')

@section('content_body')

    <form action="{{ route('autor.update', $autor->CodAu) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <x-adminlte-input name="nome" value="{{ old('nome', $autor->Nome) }}" label="Nome" placeholder="Nome do autor"
                              fgroup-class="col-md-12"/>
        </div>

        <div class="row">
            <x-adminlte-button fgroup-class="col-md-12" class="btn-flat" type="submit" label="Salvar" theme="success" icon="fas fa-lg fa-save"/>
        </div>
    </form>

    @if(session('error'))
        <br>
        <x-adminlte-alert theme="warning" title="Warning" dismissable>
            {{ session('error') }}
        </x-adminlte-alert>
    @endif

    @if($errors->any())
        <br>
        <x-adminlte-alert theme="danger" title="Erro" dismissable>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </x-adminlte-alert>
    @endif
@stop
