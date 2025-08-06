<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\ConsumosPorProdutoReport;
use App\Http\Controllers\Reports\ConsumosPorProdutoSimplificadoReport;
use App\Http\Requests\ConsumosPorProdutoReportCreateFormRequest;
use App\Models\Produtos;
use App\Models\VwEstoqueSaidas;
use Carbon\Carbon;

class ConsumosPorProdutoController extends Controller
{
    public function createConsumosPorProduto()
    {
        $produtos = Produtos::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'asc')
        ->get();

        return view('reports.consumos_por_produto.create')
        ->with('produtos', $produtos);
    }

    public function generateConsumosPorProduto(ConsumosPorProdutoReportCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $estoque_saidas = [];

        $saidas = VwEstoqueSaidas::where('filial_id', session('_filial')->id)
        ->where('tipo_destino', '<>', 7) // Estornos
        ->whereBetween('data_movimento', [$data['data_inicial'], $data['data_final']]);

        if (isset($data['produtos'])) {
            $saidas->whereIn('produto_id', $data['produtos']);
        }

        $saidas = $saidas->orderBy('produto_nome')->get();

        $count = $saidas->count();

        foreach ($saidas as $saida) {

            $estoque_saidas[$saida->produto_id]['produto'] = $saida->produto;

            $estoque_saidas[$saida->produto_id]['consumos'][$count]['saida'] = $saida;
            $estoque_saidas[$saida->produto_id]['consumos'][$count]['date'] = $saida->data_movimento;

            $count ++;

        }

        $document = new ConsumosPorProdutoReport;

        if (isset($data['simplificado']) && $data['simplificado'] == 'on') {
            $document = new ConsumosPorProdutoSimplificadoReport;
        }

        $document->MakeDocument([$estoque_saidas, $data['data_inicial'], $data['data_final']]);
        
        $fileName = 'consumos_por_produto_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
