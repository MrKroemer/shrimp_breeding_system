<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwAplicacoesInsumos;
use App\Models\AplicacoesInsumos;
use App\Models\ReceitasLaboratoriais;
use App\Http\Controllers\Util\DataPolisher;

class AplicacoesInsumosAjustesController extends Controller
{
    private $divisor = 500;

    public function createAplicacoesInsumosAjustes(Request $request, int $aplicacao_insumo_grupo_id = 0)
    {
        $data = $request->all();

        $unidade_receita = '';
        $total_receita   = 0;
        $total_solvente  = 0;
        $total_solucao   = 0;
        $qtd_base        = 0;
        $total_ajuste       = 0;
        $receitas_aplicadas     = [];
        $aplicacoes_por_receita = [];

        if (! isset($data['receita_laboratorial_id'])) {
            $data['receita_laboratorial_id'] = 0;
        }
        
        if (! isset($data['data_aplicacao'])) {
            $data['data_aplicacao'] = date('d/m/Y');
        }

        $listingParam = [
            'data_aplicacao' => $data['data_aplicacao'],
            'filial_id'      => session('_filial')->id,
        ];

        if ($aplicacao_insumo_grupo_id > 0) {
            $listingParam['aplicacao_insumo_grupo_id'] = $aplicacao_insumo_grupo_id;
        }

        $aplicacoes_insumos = VwAplicacoesInsumos::listing($listingParam);

        foreach ($aplicacoes_insumos as $aplicacao_insumo) {

            foreach ($aplicacao_insumo->aplicacoes_insumos_receitas as $aplicacao_receita) {

                $receita_id = $aplicacao_receita->receita_laboratorial_id;

                $receitas_aplicadas[$receita_id] = $receita_id;

                if ($receita_id == $data['receita_laboratorial_id']) {

                    $aplicacoes_por_receita[$aplicacao_insumo->id] = $aplicacao_insumo;

                    $total_receita += $aplicacao_receita->quantidade;
                    $total_solvente += $aplicacao_receita->solvente;

                }

            }

        }

        $receitas_laboratoriais = ReceitasLaboratoriais::ativos()
        ->where('receita_laboratorial_tipo_id', 4) // INSUMOS PARA MANEJO
        ->whereIn('id', $receitas_aplicadas)
        ->orderBy('nome')
        ->get();

        $possuiEstaReceita = in_array($data['receita_laboratorial_id'], $receitas_aplicadas);

        if ($data['receita_laboratorial_id'] > 0 && $possuiEstaReceita) {

            $unidade_receita = $receitas_laboratoriais->where('id', $data['receita_laboratorial_id'])->first()->unidade_medida->sigla;

            $aplicacoes_insumos = $aplicacoes_por_receita;

            $total_solucao = $total_receita + $total_solvente;

            $fator = ceil($total_solucao / $this->divisor); // Arredonda para cima

            $qtd_base = $fator * $this->divisor;

            $total_ajuste = $qtd_base - $total_solucao;

        }

        return view('admin.aplicacoes_insumos_grupos.ajustes_solucoes.create')
        ->with('data_aplicacao',            $data['data_aplicacao'])
        ->with('receita_laboratorial_id',   $data['receita_laboratorial_id'])
        ->with('aplicacao_insumo_grupo_id', $aplicacao_insumo_grupo_id)
        ->with('divisor',                   $this->divisor)
        ->with('unidade_receita',           $unidade_receita)
        ->with('total_receita',             $total_receita)
        ->with('total_solvente',            $total_solvente)
        ->with('total_solucao',             $total_solucao)
        ->with('qtd_base',                  $qtd_base)
        ->with('total_ajuste',              $total_ajuste)
        ->with('possuiEstaReceita',         $possuiEstaReceita)
        ->with('aplicacoes_insumos',        $aplicacoes_insumos)
        ->with('receitas_laboratoriais',    $receitas_laboratoriais);

    }

    public function storeAplicacoesInsumosAjustes(Request $request, int $aplicacao_insumo_grupo_id = 0) 
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

        $aplicacoes_insumos = AplicacoesInsumos::where('data_aplicacao', $data['data_aplicacao'])
        ->where('filial_id', session('_filial')->id)
        ->get();

        if (! isset($data['receita_laboratorial_id'])) {
            $data['receita_laboratorial_id'] = 0;
        }

        if (! isset($data['data_aplicacao'])) {
            $data['data_aplicacao'] = date('Y-m-d');
        }

        $redirectParam = [
            'aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id,
            'receita_laboratorial_id'   => $data['receita_laboratorial_id'],
            'data_aplicacao'            => $data['data_aplicacao'],
        ];

        $redirect = redirect()->route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.ajustes.to_create', $redirectParam);

        foreach ($aplicacoes_insumos as $aplicacao_insumo) {

            foreach ($aplicacao_insumo->aplicacoes_insumos_receitas as $receita) {

                if ($receita->receita_laboratorial_id == $data['receita_laboratorial_id']) {

                    $receita->solvente = isset($data["solvente_{$receita->id}"]) ? $data["solvente_{$receita->id}"] : 0;

                    if (! $receita->save()) {
                        return $redirect->with('error', 'Não foi possível registrar alguns dos ajustes! Por favor, tente novamente.');
                    }

                }

            }

        }

        return $redirect->with('success', 'Ajustes realizados com sucesso!');
    }
}
