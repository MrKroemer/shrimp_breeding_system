<?php

namespace App\Http\Controllers\Charts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConsumoRacaoChartCreateFormRequest;
use App\Models\Ciclos;
use App\Models\VwCiclosPorSetor;
use App\Models\Arracoamentos;
use Carbon\Carbon;

class ConsumoRacaoChartController extends Controller
{
    private $rowsPerPage = 10;

    public function createConsumoRacaoChart(int $listagem = 1)
    {
        $ciclos_ativos = VwCiclosPorSetor::where('filial_id', session('_filial')->id)
        ->where('ciclo_tipo', 1)
        ->where('ciclo_situacao','<', 8 ) // Ativo
        ->orderBy('tanque_sigla')
        ->get();

        $ciclos_inativos = VwCiclosPorSetor::where('filial_id', session('_filial')->id)
        ->where('ciclo_tipo', 1)
        ->where('ciclo_situacao', 8) // Encerrado
        ->orderBy('tanque_sigla')
        ->orderBy('ciclo_numero')
        ->get();

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('tipo', 1)
        ->whereBetween('situacao', [5, 8]) // Povoamento, Engorda, Despesca, Encerrado
        //->orWhere('tanque_id')
        ->orderBy('numero', 'desc')
        ->get();

        return view('charts.consumo_racao.create')
        ->with('ciclos_ativos',    $ciclos_ativos)
        ->with('ciclos_inativos',  $ciclos_inativos)
        ->with('ciclos',           $ciclos)
        ->with('listagem',         $listagem);
    }

    public function generateConsumoRacaoChart(ConsumoRacaoChartCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data_inicial = Carbon::createFromFormat('d/m/Y', $data['data_inicial']);
        $data_final = Carbon::createFromFormat('d/m/Y', $data['data_final']);

        if ($data_inicial->greaterThan($data_final)) {
            return redirect()->back()
            ->with('warning', 'Informe uma data final posterior a inicial.');
        }

        $graficos = [];

        foreach ($data as $key => $value) {

            if (strpos($key, 'ciclo_') === 0) {

                $ciclo_id = str_replace('ciclo_', '', $key);

                $ciclo = Ciclos::find($ciclo_id);

                $arracoamentos = Arracoamentos::where('ciclo_id', $ciclo_id)
                ->whereBetween('data_aplicacao', [$data_inicial, $data_final])
                ->orderBy('data_aplicacao')
                ->get();

                $graficos[] = [$ciclo, $arracoamentos];
            
            }

        }

        return view('charts.consumo_racao.chart')
        ->with('graficos', $graficos);
    }
}
