<?php

namespace App\Http\Controllers\Charts;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Ciclos;
use App\Models\Setores;
use App\Models\AnalisesBiometricasAmostras;
use App\Http\Requests\AmostrasCrescimentoCreateFormRequest;
use App\Models\VwCiclosPorSetor;
use Carbon\Carbon;

class AmostrasCrescimentoChartController extends Controller
{

    public function createAmostrasCrescimentoChart(int $listagem = 1){

        $ciclos_ativos = VwCiclosPorSetor::where('filial_id', session('_filial')->id)
                         ->where('ciclo_tipo', 1)
                         ->where('ciclo_situacao', '<', 8) // Ciclo ativo
                         ->orderBy('tanque_sigla')
                         ->orderBy('ciclo_numero')
                         ->get();

        $ciclos_inativos = VwCiclosPorSetor::where('filial_id', session('_filial')->id)
                          ->where('ciclo_tipo', 1)
                          ->where('ciclo_situacao', 8)
                          ->orderBy('tanque_sigla')
                          ->orderBy('ciclo_numero')
                          ->get();

        $setores = Setores::where('filial_id', session('_filial')->id)
                   ->whereNotIn('id', [1, 8, 14, 15])
                   ->orderBy('nome', 'desc')
                   ->get();
                
        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
                  ->where('tipo', 1)
                  ->whereBetween('situacao', [5, 8])
                  ->orderBy('numero', 'desc')
                  ->get();
        
        return view('charts.amostras.create')
                  ->with('ciclos_ativos',      $ciclos_ativos)
                  ->with('ciclos_inativos',    $ciclos_inativos)
                  ->with('ciclos',             $ciclos)
                  ->with('listagem',           $listagem)
                  ->with('setores',            $setores);                     

    }

        public function generateAmostrasCrescimentoChart(AmostrasCrescimentoCreateFormRequest $request){

            
            
            $data = $request->except(['_token']);
            $data_inicial = Carbon::createFromFormat('d/m/Y', $data['data_inicio']);
            $data_final = Carbon::createFromFormat('d/m/Y', $data['data_final']);
            $ciclos = [];
            $graficos = [];

            foreach($data as $key => $value){
                if(strpos($key, 'ciclo_') === 0){
                    $ciclo_id = str_replace('ciclo_', '', $key);
                    $ciclo = Ciclos::find($ciclo_id);
                    

                    $analise_biometrica_id = str_replace('analise_biometrica_id', '', $key);
                    $biometriasAmostras = AnalisesBiometricasAmostras::where('analise_biometrica_id', $analise_biometrica_id)
                                                                     ->whereBetween('data_analise', [$data_final])
                                                                     ->get();
                                                                                                                                          -
                  
                    $graficos[] = [$ciclo, $biometriasAmostras];                                                
     
        }
    }
            
            return view('charts.amostras.chart')
                   ->with('data', $data_final)
                   ->with('data', $data_inicial)
                   ->with('ciclos', $ciclos);

    }

}