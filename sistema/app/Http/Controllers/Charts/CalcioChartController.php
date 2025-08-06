<?php

namespace App\Http\Controllers\Charts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwCalcicasPorTanque;
use App\Models\Setores;
use App\Models\Tanques;
use App\Models\VwBalancoIonico;
use Carbon\Carbon;

class CalcioChartController extends Controller
{
    private $rowsPerPage = 10;

    public function createCalcioChart(int $listagem = 1)
    {
        $setores = Setores::where('filial_id', session('_filial')->id)
        ->whereNotIn('id', [14,15])
        ->orderBy('nome', 'desc')
        ->get();

        return view('charts.calcio.create')
        ->with('listagem',   $listagem)
        ->with('setores',    $setores);
    }

    public function generateCalcioChart(Request $request)
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
            
                $amostras = VwBalancoIonico::where('tanque_id', $tanque_id)
                ->whereBetween('data_coleta', [$data_inicial, $data_final])
                ->whereNotNull('calcio')
                ->orderBy('data_coleta')
                ->get();

                foreach ($amostras as $amostra) {

                    $amostraDataCalcio = $amostra->data_coleta('Ymd');

                    $graficos[$tanque_id][$amostraDataCalcio] = $amostra;

                }
            }

        }

        return view('charts.calcio.chart')
        ->with('tanques',  $tanques)
        ->with('graficos', $graficos);
    }
}
