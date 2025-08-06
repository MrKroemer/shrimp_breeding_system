<?php

namespace App\Http\Controllers\Charts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClassificacaoChartCreateFormRequest;
use App\Models\Ciclos;
use App\Models\VwCiclosPorSetor;
use App\Models\Setores;
use App\Models\AnalisesBiometricas;
use Carbon\Carbon;

class ClassificacaoChartController extends Controller
{
    private $rowsPerPage = 10;

    public function createClassificacaoChart(int $listagem = 1)
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

        return view('charts.classificacao.create')
        ->with('ciclos_ativos',    $ciclos_ativos)
        ->with('ciclos_inativos',  $ciclos_inativos)
        ->with('ciclos',           $ciclos)
        ->with('listagem',         $listagem)
        ->with('setores',          $setores);
    }

    public function generateClassificacaoChart(ClassificacaoChartCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data_inicial = Carbon::createFromFormat('d/m/Y', $data['data_inicial']);
        $data_final = Carbon::createFromFormat('d/m/Y', $data['data_final']);

        if ($data_inicial->greaterThan($data_final)) {
            return redirect()->back()
            ->with('warning', 'Informe uma data final posterior a inicial.');
        }

        /* $classificacoes = [
            '0/10',
            '10/20',
            '20/30',
            '30/40',
            '40/50',
            '50/60',
            '60/70',
            '70/80',
            '80/100',
            '100/120',
            '120/150',
            '150/200',
            '200/330',
            '330/1000',
        ]; */

        $graficos = [];

        foreach ($data as $key => $value) {

            if (strpos($key, 'ciclo_') === 0) {

                $ciclo_id = str_replace('ciclo_', '', $key);

                $ciclo = Ciclos::find($ciclo_id);
                
                $biometria = AnalisesBiometricas::where('ciclo_id', $ciclo_id)
                ->whereBetween('data_analise', [$data_inicial, $data_final])
                ->orderBy('data_analise', 'desc')
                ->first();

                if ($biometria instanceof AnalisesBiometricas) {

                    $total_amostras = 0;
                    $classificacoes = [];

                    $total_amostras += $biometria->amostras->count();

                    foreach ($biometria->classificacoes() as $classificacao => $qtd_amostras) {

                        if (isset($classificacoes[$classificacao])) {
                            $classificacoes[$classificacao] += $qtd_amostras;
                        } else {
                            $classificacoes[$classificacao] = $qtd_amostras;
                        }

                    }

                    foreach ($classificacoes as $classificacao => $qtd_amostras) {

                        if ($total_amostras != 0) {

                            $search = ['classe', 'to'];
                            $replace = ['', '/'];

                            $classificacoes[str_replace($search, $replace, $classificacao)] = ($qtd_amostras / $total_amostras) * 100;

                            unset($classificacoes[$classificacao]);

                        }

                    }

                    $classificacoes = array_reverse($classificacoes);

                    $graficos[] = [$ciclo, $classificacoes, $data_final, $biometria];

                }
            }

        }

        return view('charts.classificacao.chart')
        ->with('graficos', $graficos);
    }
}
