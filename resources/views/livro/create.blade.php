@extends('template.main')

@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)
@section('plugins.Inputmask', true)

@section('subtitle', 'Livro - Cadastro')
@section('content_header_title', 'Cadastro')
@section('content_header_subtitle', 'Livro')

@section('content_body')

    <form action="{{ route('livro.store') }}" method="POST">
        @csrf
        <div class="row">
            <x-adminlte-input name="titulo" value="{{ old('titulo') }}" label="Titulo" placeholder="Titulo do livro"
                              fgroup-class="col-md-12"/>
        </div>
        <div class="row">
            <x-adminlte-input name="editora" value="{{ old('editora') }}" label="Editora" placeholder="Editora do livro"
                              fgroup-class="col-md-12"/>
        </div>
        <div class="row">
            <x-adminlte-input name="edicao" value="{{ old('edicao') }}" label="Edição" placeholder="Edição do livro"
                              fgroup-class="col-md-12"/>
        </div>
        <div class="row">
            <x-adminlte-input name="ano_publicacao" value="{{ old('ano_publicacao') }}" label="Ano de publicação" placeholder="Ex: 1996"
                              fgroup-class="col-md-12"/>
        </div>
        <div class="row">
            <x-adminlte-input id="valor" name="valor" value="{{ old('valor') }}" label="Valor" placeholder="Valor do livro"
                              fgroup-class="col-md-4"/>
        </div>

        <div class="row">
            <label for="autor_id" class="col-md-6">
                Autores <br> <br>
                <select class="select2 form-control" name="autor_id[]" multiple="multiple">
                    <option value="">Escolha os autores do livro</option>
                    @foreach($autores as $autor)
                        <option {{ in_array($autor->CodAu, old('autor_id', [])) ? 'selected' : '' }}
                                value="{{ $autor->CodAu }}">
                            {{ $autor->Nome }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="row">
            <label for="" class="col-md-6">
                Assuntos <br> <br>
                <select class="select2 form-control" name="assunto_id[]" multiple="multiple">
                    <option value="">Escolha os assuntos do livro</option>
                    @foreach($assuntos as $assunto)
                        <option {{ in_array($assunto->CodAs, old('assunto_id', [])) ? 'selected' : '' }}
                                value="{{ $assunto->CodAs }}">
                            {{ $assunto->Descricao }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="row col-12">
            <x-adminlte-button fgroup-class="col-md-12" class="btn-flat" type="submit" label="Salvar" theme="success" icon="fas fa-lg fa-save"/>
        </div>
        <br>
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

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('#valor').inputmask("decimal", {
                'alias': 'numeric',
                'groupSeparator': '.',
                'autoGroup': true,
                'digits': 2,
                'radixPoint': ",",
                'digitsOptional': true,
                'allowMinus': false,
                'prefix': 'R$ ',
                'rightAlign': false,
                'placeholder': '',
                'removeMaskOnSubmit': true
            });
        });
    </script>
@endsection
