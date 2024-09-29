<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuntoRequest;
use App\Models\Assunto;
use Biblioteca\Livros\Application\UseCase\CriarAssunto;
use Illuminate\Http\Request;

class AssuntoController extends Controller
{
    public function index()
    {
        $assuntos = Assunto::paginate(10);
        return view('assunto.index', compact('assuntos'));
    }

    public function store(AssuntoRequest $request)
    {
        $validated = $request->validated();
        app()->make(CriarAssunto::class)->handle($validated);
    }

    public function update(AssuntoRequest $request, $id)
    {
        $validated = $request->validated();
        dd($validated);
    }

    public function destroy($id)
    {
        $assunto = Assunto::find($id);
        $assunto->delete();
        return redirect()->route('assunto.index');
    }

    public function create()
    {
        return view('assunto.create');
    }

    public function edit($id)
    {
        $assunto = Assunto::find($id);
        return view('assunto.edit', compact('assunto'));
    }
}
