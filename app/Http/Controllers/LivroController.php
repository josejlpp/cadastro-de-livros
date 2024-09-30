<?php

namespace App\Http\Controllers;

use App\Helper\LivroHelper;
use App\Http\Requests\LivroRequest;
use App\Models\Assunto;
use App\Models\Autor;
use App\Models\Livro;
use Biblioteca\Livros\Application\UseCase\AtualizarLivro;
use Biblioteca\Livros\Application\UseCase\CriarLivro;
use DomainException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        DB::beginTransaction();
        try {
            $livroDto = LivroHelper::buildLivroDtoFromRequest($validated);
            app()->make(CriarLivro::class)->handle($livroDto);
            DB::commit();
        } catch (DomainException $de) {
            DB::rollBack();
            Log::error($de->getMessage(), $de->getTrace());
            return redirect()->route('livro.create')
                ->with('error', $de->getMessage())
                ->withInput();
        }
        catch (QueryException $qe) {
            DB::rollBack();
            Log::error($qe->getMessage(), $qe->getTrace());
            return redirect()->route('livro.create')
                ->with('error', 'Erro inesperado no banco de dadods ao criar livro')
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $e->getTrace());
            return redirect()->route('livro.create')->with('error', 'Erro inesperado ao criar livro');
        }

        return redirect()->route('livro.index')->with('success', 'Livro criado com sucesso');
    }

    public function update(LivroRequest $request, $id)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $livroDto = LivroHelper::buildLivroDtoFromRequest($validated, $id);
            app()->make(AtualizarLivro::class)->handle($livroDto);
            DB::commit();
        } catch (DomainException $de) {
            DB::rollBack();
            Log::error($de->getMessage(), $de->getTrace());
            return redirect()->route('livro.edit', $id)
                ->with('error', $de->getMessage())
                ->withInput();
        }
        catch (QueryException $qe) {
            DB::rollBack();
            Log::error($qe->getMessage(), $qe->getTrace());
            return redirect()->route('livro.edit', $id)
                ->with('error', 'Erro inesperado no banco de dadods ao criar livro')
                ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $e->getTrace());
            return redirect()->route('livro.edit', $id)->with('error', 'Erro inesperado ao criar livro');
        }

        return redirect()->route('livro.index')->with('success', 'Livro atualizado com sucesso');
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
        $livro = Livro::with('assuntos', 'autores')->find($id);
        $autores = Autor::all();
        $assuntos = Assunto::all();

        return view('livro.edit', compact('livro', 'autores', 'assuntos'));
    }
}
