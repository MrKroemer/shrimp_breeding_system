<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\ConsumosPorCicloReport;
use App\Http\Requests\ConsumosPorCicloReportCreateFormRequest;
use App\Models\Ciclos;
use App\Models\Produtos;
use App\Models\VwEstoqueSaidas;
use Carbon\Carbon;

class ConsumosPorCicloController extends Controller
{
    public function createConsumosPorCiclo()
    {
        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->whereBetween('situacao', [1, 8]) // Povoamento, Engorda, Despesca, Encerrado
        ->orderBy('numero', 'desc')
        ->get();

        $produtos = Produtos::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'asc')
        ->get();

        return view('reports.consumos_por_ciclo.create')
        ->with('ciclos',   $ciclos)
        ->with('produtos', $produtos);
    }

    public function generateConsumosPorCiclo(ConsumosPorCicloReportCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        if (isset($data['ciclos'])) {
            $ciclos = Ciclos::whereIn('id', $data['ciclos'])
            ->get();
        } else {
            $ciclos = Ciclos::where('filial_id', session('_filial')->id)
            ->whereBetween('situacao', [5, 8]) // Povoamento, Engorda, Despesca, Encerrado
            ->whereBetween('data_inicio', [$data['data_inicial'], $data['data_final']])
            ->orderBy('numero', 'desc')
            ->get();
        }

        $estoque_saidas = [];

        foreach ($ciclos as $ciclo) {

            $saidas = VwEstoqueSaidas::where('filial_id', session('_filial')->id)
            ->where('ciclo_id', $ciclo->id)
            ->whereBetween('data_movimento', [$data['data_inicial'], $data['data_final']]);

            if (isset($data['produtos'])) {
                $saidas->whereIn('produto_id', $data['produtos']);
            }

            $saidas->orderBy('data_movimento', 'asc')
                   ->orderBy('produto_nome',   'asc');

            $estoque_saidas[$ciclo->id] = $saidas->get();

        }

        $document = new ConsumosPorCicloReport;
        
        $document->MakeDocument([$ciclos, $estoque_saidas, $data['data_inicial'], $data['data_final']]);
        
        $fileName = 'consumos_por_ciclo_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
