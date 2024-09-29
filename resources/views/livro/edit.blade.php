<?php
<?php
@extends('template.main')

@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)

@section('subtitle', 'Livro - Cadastro')
@section('content_header_title', 'Cadastro')
@section('content_header_subtitle', 'Livro')

@section('content_body')

    <form action="{{ route('employee.update', $livro->Codl) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <x-adminlte-input name="titulo" value="{{ old('titulo', $livro->titulo) }}" label="Titulo" placeholder="Titulo do livro"
                              fgroup-class="col-md-12"/>
        </div>
        <div class="row">
            <x-adminlte-input name="editora" value="{{ old('editora', $livro->editora) }}" label="Editora" placeholder="Editora do livro"
                              fgroup-class="col-md-12"/>
        </div>
        <div class="row">
            <x-adminlte-input name="edicao" value="{{ old('edicao', $livro->edicao) }}" label="Edição" placeholder="Edição do livro"
                              fgroup-class="col-md-12"/>
        </div>
        <div class="row">
            <x-adminlte-input name="anoPublicacao" value="{{ old('anoPublicacao', $livro->anoPublicacao) }}" label="Ano de publicação" placeholder="Ex: 1996"
                              fgroup-class="col-md-12"/>
        </div>

        <div class="row">
            <x-adminlte-select2 name="autor_id[]"  label="Vacina" fgroup-class="col-md-4" multiple>
                <option value="">Escolha os autores do livro</option>
                @foreach($autores as $autor)
                    <option {{ old('autor_id') == $autor->Codl ? 'selected' : '' }}
                            value="{{ $autor->Codl }}">
                        {{ $autor->nome }}
                    </option>
                @endforeach
            </x-adminlte-select2>
        </div>

        <div class="row">
            <x-adminlte-select2 name="assunto_id[]"  label="Vacina" fgroup-class="col-md-4" multiple>
                <option value="">Escolha os assuntos do livro</option>
                @foreach($assuntos as $assunto)
                    <option {{ old('assunto_id') == $assunto->Codl ? 'selected' : '' }}
                            value="{{ $assunto->Codl }}">
                        {{ $assunto->nome }}
                    </option>
                @endforeach
            </x-adminlte-select2>
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
