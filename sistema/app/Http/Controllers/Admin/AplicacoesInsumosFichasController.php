<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\AplicacoesInsumosFichasReport;
use App\Models\AplicacoesInsumos;
use App\Models\AplicacoesInsumosGrupos;
use Carbon\Carbon;

class AplicacoesInsumosFichasController extends Controller
{
    public function createAplicacoesInsumosFichas()
    {
        return view('reports.fichas_aplicacoes_insumos.create');
    }

    public function generateAplicacoesInsumosFichas(Request $request)
    {
        $data = $request->except(['_token']);

        if (empty($data['data_aplicacao'])) {
            return redirect()->back()
            ->with('warning', 'Informe uma data para gerar o relatório.');
        }

        $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao'])->format('Y-m-d');
        
        $listingParam = [
            'data_aplicacao' => $data_aplicacao,
            'filial_id'      => session('_filial')->id,
        ];

        if (isset($data['aplicacao_insumo_grupo_id']) && $data['aplicacao_insumo_grupo_id'] > 0) {
            $listingParam['aplicacao_insumo_grupo_id'] = $data['aplicacao_insumo_grupo_id'];
        }

        if (! isset($data['receita_laboratorial_id'])) {
            $data['receita_laboratorial_id'] = 0;
        }

        $aplicacoes_insumos = AplicacoesInsumos::listing($listingParam);

        $aplicacao_insumo_grupo = AplicacoesInsumosGrupos::find($data['aplicacao_insumo_grupo_id']);

        $document = new AplicacoesInsumosFichasReport();
        
        $document->MakeDocument([
            $aplicacoes_insumos, 
            $aplicacao_insumo_grupo,
            $data['data_aplicacao'], 
            $data['receita_laboratorial_id'], 
        ]);
        
        $fileName = 'ficha_aplicacoes_insumos_' . date('d-m-Y');

        return $document->Output($fileName, 'I');
    }
}
