<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    public function index()
    {
        $livros = Livro::paginate(10);
        return view('livro.index', compact('livros'));
    }

    public function show($id)
    {
        $livro = Livro::find($id);
        return view('livro.show', compact('livro'));
    }

    public function store(LivroRequest $request)
    {
        $validated = $request->validated();
        dd($validated);
    }

    public function update(LivroRequest $request, $id)
    {
        $validated = $request->validated();
        dd($validated);
    }

    public function destroy($id)
    {
        $livro = Livro::find($id);
        $livro->delete();
        return redirect()->route('livro.index');
    }

    public function create()
    {
        $autores = Autor::all();
        $assuntos = Assunto::all();

        return view('livro.create', compact('autores', 'assuntos'));
    }

    public function edit($id)
    {
        $livro = Livro::find($id);
        $autores = Autor::all();
        $assuntos = Assunto::all();

        return view('livro.edit', compact('livro', 'autores', 'assuntos'));
    }
}
