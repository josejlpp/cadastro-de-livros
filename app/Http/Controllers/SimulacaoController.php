<?php

namespace App\Http\Controllers;

use App\Models\Simulacao;

class SimulacaoController extends Controller
{
    public function index()
    {
        $simulacoes = Simulacao::orderByDesc('id')->paginate(10);
        return view('simulacao.index', compact('simulacoes'));
    }

    public function show($id)
    {
        $simulacao = Simulacao::findOrFail($id);
        return view('simulacao.show', compact('simulacao'));
    }

    public function destroy($id)
    {
        Simulacao::findOrFail($id)->delete();
        return redirect()->route('simulacao.index')->with('success', 'Simulação excluída com sucesso');
    }
}
