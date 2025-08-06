<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResumoSaidasProdutosReportCreateFormRequest;
use App\Models\Produtos;
use App\Models\VwEstoqueSaidas;
use Carbon\Carbon;

class ResumoSaidasProdutosController extends Controller
{
    public function createResumoSaidasProdutos()
    {
        $produtos = Produtos::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'asc')
        ->get();

        return view('reports.resumo_saidas_produtos.create')
        ->with('produtos', $produtos);
    }

    public function generateResumoSaidasProdutos(ResumoSaidasProdutosReportCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $filial_id    = session('_filial')->id;
        $data_inicial = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');
        $data_final   = Carbon::createFromFormat('d/m/Y',   $data['data_final'])->format('Y-m-d');

        $produtos = [];

        if (isset($data['produtos'])) {
            $produtos = $data['produtos'];
        }

        $estoque_saidas = VwEstoqueSaidas::totaisPorProduto($filial_id, $data_inicial, $data_final, $produtos);

        $document = new ResumoSaidasProdutosReport;
        
        $document->MakeDocument([$estoque_saidas, $data['data_inicial'], $data['data_final']]);
        
        $fileName = 'resumo_saidas_produtos_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
