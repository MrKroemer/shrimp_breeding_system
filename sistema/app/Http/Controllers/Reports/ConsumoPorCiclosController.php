<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\ConsumoPorCiclosReport;
use App\Http\Requests\ConsumoPorCiclosReportCreateFormRequest;
use App\Models\Ciclos;
use App\Models\Produtos;
use App\Models\VwEstoqueSaidas;
use Carbon\Carbon;

class ConsumoPorCiclosController extends Controller
{
    public function createConsumoPorCiclos()
    {
        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->whereBetween('situacao', [5, 8]) // Povoamento, Engorda, Despesca, Encerrado
        ->orderBy('numero', 'desc')
        ->get();

        $produtos = Produtos::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'asc')
        ->get();

        return view('reports.consumo_por_ciclos.create')
        ->with('ciclos',   $ciclos)
        ->with('produtos', $produtos);
    }

    public function generateConsumoPorCiclos(ConsumoPorCiclosReportCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $ciclos = Ciclos::whereIn('id', $data['ciclos'])
        ->get();

        $estoque_saidas = [];

        foreach ($ciclos as $ciclo) {

            $saidas = VwEstoqueSaidas::where('filial_id', session('_filial')->id)
            ->where('ciclo_id', $ciclo->id)
            ->whereBetween('data_movimento', [$data['data_inicial'], $data['data_final']]);

            if (! empty($data['produtos'])) {
                $saidas->whereIn('produto_id', $data['produtos']);
            }

            $saidas->orderBy('data_movimento', 'asc')
                   ->orderBy('produto_nome',   'asc');

            $estoque_saidas[$ciclo->id] = $saidas->get();

        }

        $document = new ConsumoPorCiclosReport();
        
        $document->MakeDocument([$ciclos, $estoque_saidas, $data['data_inicial'], $data['data_final']]);
        
        $fileName = 'consumo_por_ciclos_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
