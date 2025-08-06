<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\ColetasParametros;
use App\Models\ColetasParametrosTipos;
use App\Models\Setores;
use App\Models\Tanques;
use Carbon\Carbon;

class ColetasParametrosChartController extends Controller
{
    private $rowsPerPage = 10;

    public function createColetasParametrosChart()
    {
        $parametros = ColetasParametrosTipos::orderBy('sigla')->get();

        $setores = Setores::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'desc')
        ->get();

        return view('charts.parametros.create')
        ->with('parametros', $parametros)
        ->with('setores', $setores);
    }

    public function generateColetasParametrosChart(Request $request)
    {
        $data = $request->except(['_token']);

        if (empty($data['data_inicio'])) {
            return redirect()->back()
            ->with('warning', 'Informe uma data inicial para gerar o relatório.');
        }

        if (empty($data['data_final'])) {
            return redirect()->back()
            ->with('warning', 'Informe uma data final para gerar o gráfico.');
        }

        $data_inicial = Carbon::createFromFormat('d/m/Y', $data['data_inicio']);
        $data_final = Carbon::createFromFormat('d/m/Y', $data['data_final']);

        if ($data_inicial->greaterThan($data_final)) {
            return redirect()->back()
            ->with('warning', 'Informe uma data final posterior a inicial.');
        }
        
        if (empty($data['coletas_parametros_tipos_id'])) {
            return redirect()->back()
            ->with('warning', 'Informe um parâmetro para gerar o gráfico.');
        }

        $parametro = ColetasParametrosTipos::find($data['coletas_parametros_tipos_id']);
        
        $graficos = [];

        foreach ($data as $key => $value) {
            
            if (strpos($key, 'tanque_') === 0) {

                $tanque_id = str_replace('tanque_', '', $key);

                $tanque = Tanques::find($tanque_id);

                $medicoes = [];

                if($tanque instanceof Tanques){

                    $coletas = ColetasParametros::where('setor_id', $tanque->setor_id)
                    ->where('coletas_parametros_tipos_id', $data['coletas_parametros_tipos_id'])
                    ->whereBetween('data_coleta', [$data_inicial, $data_final])
                    ->orderBy('data_coleta')
                    ->get();

                    
                    foreach($coletas as $coleta){

                        $medicao = $coleta->coletas_parametros_amostras->where('tanque_id', $tanque_id)->sortByDesc('id')->first();

                        if ($medicao) {
                            $medicoes[] = $medicao;
                        }

                    }

                }

                $graficos[] = [$tanque, $medicoes];
            }

        }

        //dd($graficos);

        return view('charts.parametros.chart')
        ->with('graficos', $graficos)
        ->with('parametro', $parametro);

    }
}
