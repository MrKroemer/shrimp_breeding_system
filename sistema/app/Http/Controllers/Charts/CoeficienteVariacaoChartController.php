<?php

namespace App\Http\Controllers\Charts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwAnalisesBiometricas;
use App\Models\Ciclos;
use App\Models\VwCiclosPorSetor;
use App\Models\Setores;
use App\Models\Tanques;
use Carbon\Carbon;

class CoeficienteVariacaoChartController extends Controller
{
    private $rowsPerPage = 10;

    public function createCoeficienteVariacaoChart(int $listagem = 1)
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

        $setores = Setores::where('filial_id', session('_filial')->id)
        ->whereNotIn('id', [14,15])
        ->orderBy('nome', 'desc')
        ->get();

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('tipo', 1)
        ->whereBetween('situacao', [5, 8]) // Povoamento, Engorda, Despesca, Encerrado
        //->orWhere('tanque_id')
        ->orderBy('numero', 'desc')
        ->get();

        return view('charts.coeficiente_variacao.create')
        ->with('ciclos_ativos',    $ciclos_ativos)
        ->with('ciclos_inativos',  $ciclos_inativos)
        ->with('ciclos',           $ciclos)
        ->with('listagem',         $listagem)
        ->with('setores',          $setores);
    }

    public function generateCoeficienteVariacaoChart(request $request)
    {
        $data = $request->except(['_token']);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('tipo', 1)
        ->whereBetween('situacao', [6, 7]) // Povoamento, Engorda, Despesca, Encerrado
        ->orderBy('numero', 'desc')
        ->get(['id','tanque_id']);

        if(! isset($data['ciclos'])){
           foreach($ciclos as $ciclo){
            $data['ciclos'][] = $ciclo->id;
           }
        }
        $ciclos = "";
        
        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->orderByDesc('id')
        ->get();

        $graficos = [];
        
        foreach ($data as $key => $value) {

            if (strpos($key, 'ciclo_') === 0) {

                $ciclo_id = str_replace('ciclo_', '', $key);
                

                $amostras = VwAnalisesBiometricas::where('ciclo_id', $ciclo_id)
                ->orderBy('data_analise')
                ->get();

                //dump($amostras);

                foreach ($amostras as $amostra) {

                    $amostraDataAnalise = $amostra->data_analise('Ymd');

                    $graficos[$ciclo_id][$amostraDataAnalise] = $amostra;
                }
            }

        }
        //dd($data);
        return view('charts.coeficiente_variacao.chart')
        ->with('ciclos',  $ciclos)
        ->with('graficos', $graficos);
    }
}
