<?php

namespace App\Http\Controllers;

use App\Helper\LivroHelper;
use App\Http\Requests\AutorRequest;
use App\Models\Autor;
use Biblioteca\Livros\Application\UseCase\AtualizarAutor;
use Biblioteca\Livros\Application\UseCase\CriarAutor;
use DomainException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $autores = Autor::paginate(10);
        return view('autor.index', compact('autores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('autor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AutorRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $autorDto = LivroHelper::makeAutorDtoFromRequest($validated);
            app()->make(CriarAutor::class)->handle($autorDto);
            DB::commit();
        }
        catch (DomainException $de) {
            DB::rollBack();
            Log::error($de->getMessage(), $de->getTrace());
            return redirect()->route('autor.create')
                ->with('error', $de->getMessage())
                ->withInput();
        }
        catch (QueryException $qe) {
            DB::rollBack();
            Log::error($qe->getMessage(), $qe->getTrace());
            return redirect()->route('autor.create')
                ->with('error', 'Erro inesperado no banco de dadods ao criar autor')
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $e->getTrace());
            return redirect()->route('autor.create')
                ->with('error', 'Erro inesperado ao criar autor')
                ->withInput();
        }

        return redirect()->route('autor.index')->with('success', 'Autor criado com sucesso');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $autor = Autor::find($id);
        return view('autor.edit', compact('autor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AutorRequest $request, string $id)
    {
        $validated = $request->validated();
        try {
            $autorDto = LivroHelper::makeAutorDtoFromRequest($validated, $id);
            app()->make(AtualizarAutor::class)->handle($autorDto);
        }
        catch (DomainException $de) {
            Log::error($de->getMessage(), $de->getTrace());
            return redirect()->route('autor.edit', $id)
                ->with('error', $de->getMessage())
                ->withInput();
        }
        catch (QueryException $qe) {
            DB::rollBack();
            Log::error($qe->getMessage(), $qe->getTrace());
            return redirect()->route('autor.edit')
                ->with('error', 'Erro inesperado no banco de dadods ao criar autor')
                ->withInput();
        } catch (Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return redirect()->route('autor.edit', $id)->with('error', 'Erro inesperado ao atualizar autor');
        }

        return redirect()->route('autor.index')->with('success', 'Autor atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $autor = Autor::find($id);
        $autor->delete();
        return redirect()->route('autor.index');
    }
}
