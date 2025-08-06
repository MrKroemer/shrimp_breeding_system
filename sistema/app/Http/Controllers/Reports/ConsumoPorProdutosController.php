<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\ConsumoPorProdutosReport;
use App\Http\Requests\ConsumoPorProdutosReportCreateFormRequest;
use App\Models\Ciclos;
use App\Models\Produtos;
use App\Models\VwEstoqueSaidas;
use Carbon\Carbon;

class ConsumoPorProdutosController extends Controller
{
    public function createConsumoPorProdutos()
    {
        $produtos = Produtos::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'asc')
        ->get();

        return view('reports.consumo_por_produtos.create')
        ->with('produtos', $produtos);
    }

    public function generateConsumoPorProdutos(ConsumoPorProdutosReportCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $estoque_saidas = [];

        $saidas = VwEstoqueSaidas::where('filial_id', session('_filial')->id)
        ->whereIn('produto_id', $data['produtos'])
        ->whereBetween('data_movimento', [$data['data_inicial'], $data['data_final']])
        ->whereNotNull('ciclo_id')
        ->orderBy('produto_nome', 'asc')
        ->get();

        $count = $saidas->count();

        foreach ($saidas as $saida) {

            $estoque_saidas[$saida->produto_id]['produto'] = $saida->produto;

            $estoque_saidas[$saida->produto_id]['consumos'][$count]['saida'] = $saida;
            $estoque_saidas[$saida->produto_id]['consumos'][$count]['date'] = $saida->data_movimento;

            $count ++;

        }

        $document = new ConsumoPorProdutosReport();
        
        $document->MakeDocument([$estoque_saidas, $data['data_inicial'], $data['data_final']]);
        
        $fileName = 'consumo_por_produtos_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
