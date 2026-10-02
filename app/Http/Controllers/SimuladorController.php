<?php

namespace App\Http\Controllers;

use App\Helper\SimuladorHelper;
use App\Http\Requests\SimulacaoRequest;
use App\Models\Simulacao;
use Biblioteca\Simulador\Application\UseCase\RegistrarSimulacao;
use Biblioteca\Simulador\Domain\Entity\PerfilTributario;
use Biblioteca\Simulador\Domain\Entity\TeseCollection;
use Biblioteca\Simulador\Domain\Enum\RegimeTributario;
use Biblioteca\Simulador\Domain\Enum\Segmento;
use DomainException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SimuladorController extends Controller
{
    public function index()
    {
        return view('simulador.index', [
            'regimes' => RegimeTributario::opcoes(),
            'segmentos' => Segmento::opcoes(),
            'perfisPorBase' => app()->make(TeseCollection::class)->perfisPorBase(),
            'mesesMaximo' => PerfilTributario::MESES_MAXIMO,
        ]);
    }

    public function store(SimulacaoRequest $request)
    {
        $validated = $request->validated();

        try {
            $simulacaoDto = SimuladorHelper::buildSimulacaoDtoFromRequest($validated);
            $simulacao = app()->make(RegistrarSimulacao::class)->handle($simulacaoDto);
        } catch (DomainException $de) {
            Log::warning($de->getMessage());
            return redirect()->route('simulador.index')
                ->with('error', $de->getMessage())
                ->withInput();
        } catch (QueryException $qe) {
            Log::error($qe->getMessage(), ['exception' => $qe]);
            return redirect()->route('simulador.index')
                ->with('error', 'Erro inesperado no banco de dados ao registrar a simulação')
                ->withInput();
        } catch (Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return redirect()->route('simulador.index')
                ->with('error', 'Erro inesperado ao registrar a simulação')
                ->withInput();
        }

        return redirect()->route('simulador.resultado', $simulacao->uuid);
    }

    public function resultado(string $uuid)
    {
        $simulacao = Simulacao::where('uuid', $uuid)->firstOrFail();

        return view('simulador.resultado', compact('simulacao'));
    }
}
