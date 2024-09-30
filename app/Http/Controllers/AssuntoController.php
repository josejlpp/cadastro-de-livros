<?php

namespace App\Http\Controllers;

use App\Helper\LivroHelper;
use App\Http\Requests\AssuntoRequest;
use App\Models\Assunto;
use Biblioteca\Livros\Application\UseCase\AtualizarAssunto;
use Biblioteca\Livros\Application\UseCase\CriarAssunto;
use DomainException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        DB::beginTransaction();
        try {
            $assuntoDto = LivroHelper::makeAssuntoDtoFromRequest($validated);
            app()->make(CriarAssunto::class)->handle($assuntoDto);
            DB::commit();
        }
        catch (DomainException $de) {
            DB::rollBack();
            Log::error($de->getMessage(), $de->getTrace());
            return redirect()->route('assunto.create')
                ->with('error', $de->getMessage())
                ->withInput();
        }
        catch (QueryException $qe) {
            DB::rollBack();
            Log::error($qe->getMessage(), $qe->getTrace());
            return redirect()->route('assunto.create')
                ->with('error', 'Erro inesperado no banco de dadods ao criar assunto')
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $e->getTrace());
            return redirect()->route('assunto.create')
                ->with('error', 'Erro inesperado ao criar assunto')
                ->withInput();
        }

        return redirect()->route('assunto.index')->with('success', 'Assunto criado com sucesso');
    }

    public function update(AssuntoRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            $assuntoDto = LivroHelper::makeAssuntoDtoFromRequest($validated, $id);
            app()->make(AtualizarAssunto::class)->handle($assuntoDto);
        }
        catch (DomainException $de) {
            DB::rollBack();
            Log::error($de->getMessage(), $de->getTrace());
            return redirect()->route('assunto.edit', $id)
                ->with('error', $de->getMessage())
                ->withInput();
        }
        catch (QueryException $qe) {
            DB::rollBack();
            Log::error($qe->getMessage(), $qe->getTrace());
            return redirect()->route('assunto.edit')
                ->with('error', 'Erro inesperado no banco de dadods ao criar assunto')
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $e->getTrace());
            return redirect()->route('assunto.edit', $id)->with('error', 'Erro inesperado ao atualizar assunto');
        }

        return redirect()->route('assunto.index')->with('success', 'Assunto atualizado com sucesso');
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
