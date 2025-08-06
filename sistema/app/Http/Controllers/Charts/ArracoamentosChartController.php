<?php

namespace App\Http\Controllers\Charts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\DataPolisher;
use Illuminate\Support\Facades\DB;
use App\Models\Setores;
use App\Models\Tanques;
use App\Models\Arracoamentos;
use App\Models\ColetasParametros;
use App\Models\ColetasParametrosAmostras;
use App\Models\EstoqueSaidasArracoamentos;
use Carbon\Carbon;

class ArracoamentosChartController extends Controller
{
    private $rowsPerPage = 10;

    public function createArracoamentosChart()
    {
        $setores = Setores::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'desc')
        ->get();

        return view('charts.arracoamento.create')
        ->with('setores', $setores);
    }

    public function generateArracoamentosChart(Request $request)
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
        
        $graficos = [];
        $parametro = [];

        foreach ($data as $key => $value) {
            
            if (strpos($key, 'tanque_') === 0) {

                $tanque_id = str_replace('tanque_', '', $key);

                $tanque = Tanques::find($tanque_id);

                $medicoes = [];

                if($tanque instanceof Tanques){

                    $arracoamentos = Arracoamentos::where('tanque_id', $tanque_id)
                    ->get();

                    foreach($arracoamentos as $arracoamento){
                        $arracoamento->estoque_saidas_arracoamentos->each(function ($item, $key) {
                            if ($item->estoque_saida->produto->produto_tipo_id == 1) {
                                dump($item->estoque_saida->produto->nome);
                            }
                        });
                    }
                    
                    $coletas = ColetasParametrosAmostras::where('tanque_id', $tanque_id)->get();

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

        return view('charts.arracoamento.chart')
        ->with('graficos', $graficos)
        ->with('parametro', $parametro);

    }
}
