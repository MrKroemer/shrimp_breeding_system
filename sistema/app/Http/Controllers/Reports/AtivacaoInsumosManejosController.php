<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\AtivacaoInsumosManejosReport;
use App\Models\AplicacoesInsumos;
use App\Models\AplicacoesInsumosReceitas;
use App\Models\ReceitasLaboratoriais;
use Carbon\Carbon;

class AtivacaoInsumosManejosController extends Controller
{
    public function createAtivacaoInsumosManejos()
    {
        $receitas_laboratoriais = ReceitasLaboratoriais::ativos()
        ->where('receita_laboratorial_tipo_id', 4) // INSUMOS PARA MANEJO
        ->where('filial_id', session('_filial')->id) // INSUMOS PARA MANEJO
        ->get();

        return view('reports.ativacao_insumos_manejos.create')
        ->with('receitas_laboratoriais', $receitas_laboratoriais);
    }

    public function generateAtivacaoInsumosManejos(Request $request)
    {
        $data = $request->except(['_token']);

        if (empty($data['data_aplicacao'])) {
            return redirect()->back()
            ->with('warning', 'Informe uma data para gerar o relatório.');
        }

        $data_base = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao']);

        $data_aplicacao = $data_base->format('Y-m-d');
        $data_ativacao  = $data_base->addDay()->format('Y-m-d');

        $aplicacoes_insumos = AplicacoesInsumos::where('data_aplicacao', $data_aplicacao)
        ->get();

        $ativacoes_insumos = AplicacoesInsumos::where('data_aplicacao', $data_ativacao)
        ->get();

        $receita = ReceitasLaboratoriais::find($data['receita_laboratorial_id']);

        $aplicacoes = [];

        foreach ($aplicacoes_insumos as $aplicacao_insumo) {

            $aplicacao_receita = $aplicacao_insumo->aplicacoes_insumos_receitas
            ->where('receita_laboratorial_id', $receita->id)
            ->first();

            if ($aplicacao_receita instanceof AplicacoesInsumosReceitas) {

                $aplicacoes[] = [
                    'sigla'      => $aplicacao_insumo->tanque->sigla,
                    'tanque'     => $aplicacao_insumo->tanque,
                    'quantidade' => $aplicacao_receita->quantidade,
                    'solvente'   => $aplicacao_receita->solvente,
                ];

            }

        }

        $ativacoes = [];

        foreach ($ativacoes_insumos as $ativacao_insumo) {

            $ativacao_receita = $ativacao_insumo->aplicacoes_insumos_receitas
            ->where('receita_laboratorial_id', $receita->id)
            ->first();

            if ($ativacao_receita instanceof AplicacoesInsumosReceitas) {

                $ativacoes[] = [
                    'tanque'     => $ativacao_insumo->tanque->sigla,
                    'quantidade' => $ativacao_receita->quantidade,
                    'solvente'   => $ativacao_receita->solvente,
                ];

            }

        }

        $data_aplicacao = Carbon::parse($data_aplicacao)->format('d/m/Y');
        $data_ativacao  = Carbon::parse($data_ativacao)->format('d/m/Y');

        $document = new AtivacaoInsumosManejosReport;
        
        $document->MakeDocument([$receita, collect($aplicacoes), $data_aplicacao, $ativacoes, $data_ativacao]);
        
        $fileName = 'ativacao_insumos_manejos_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
