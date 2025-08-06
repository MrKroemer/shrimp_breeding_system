<?php

namespace App\Http\Controllers\Charts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ColetasParametrosChartCreateFormRequest;
use App\Models\VwColetasParametrosAmostras;
use App\Models\ColetasParametrosTipos;
use App\Models\Setores;
use App\Models\Tanques;
use Carbon\Carbon;

class ColetasParametrosChartController extends Controller
{
    private $rowsPerPage = 10;

    public function createColetasParametrosChart(int $listagem = 1)
    {
        $parametros = ColetasParametrosTipos::orderBy('descricao')
        ->get();

        $setores = Setores::where('filial_id', session('_filial')->id)
        ->whereNotIn('id', [14,15])
        ->orderBy('nome', 'desc')
        ->get();

        return view('charts.parametros.create')
        ->with('parametros', $parametros)
        ->with('listagem',   $listagem)
        ->with('setores',    $setores);
    }

    public function generateColetasParametrosChart(Request $request)
    {
        $data = $request->except(['_token']);

        //dd($data);

        $data_inicial = Carbon::createFromFormat('d/m/Y', $data['data_inicial']);
        $data_final   = Carbon::createFromFormat('d/m/Y', $data['data_final']);

        if ($data_inicial->greaterThan($data_final)) {
            return redirect()->back()
            ->with('warning', 'Informe uma data final posterior a inicial.');
        }

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->orderBy('sigla')
        ->get();

        $graficos = [];

        foreach ($data as $key => $value) {

            if (strpos($key, 'tanque_') === 0) {

                $tanque_id = str_replace('tanque_', '', $key);
            

                foreach ($data['parametros'] as $parametro_id) {

                    $amostras = VwColetasParametrosAmostras::where('tanque_id', $tanque_id)
                    ->where('coleta_parametro_tipo_id', $parametro_id)
                    ->whereBetween('data_coleta', [$data_inicial, $data_final])
                    ->whereNotNull('valor')
                    ->orderBy('data_coleta')
                    ->orderBy('medicao')
                    ->get();

                    foreach ($amostras as $amostra) {

                        $amostraDataMedicao = $amostra->data_coleta('Y-m-d') . $amostra->medicao;

                        $graficos[$tanque_id][$parametro_id][$amostraDataMedicao] = $amostra;

                    }

                }
            }

        }
        //dd($amostras);
        return view('charts.parametros.chart')
        ->with('tanques',  $tanques)
        ->with('graficos', $graficos);
    }
}
