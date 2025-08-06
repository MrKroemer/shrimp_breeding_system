<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ReceitasLaboratoriais;
use App\Models\VwEstoqueDisponivel;
use App\Models\AplicacoesInsumos;

class AplicacoesInsumosItensController extends Controller
{
    public function listingAplicacoesInsumosItens(int $aplicacao_insumo_id)
    {
        $aplicacao_insumo = AplicacoesInsumos::find($aplicacao_insumo_id);

        $receitas_registradas = [];
        $produtos_registrados = [];

        foreach ($aplicacao_insumo->aplicacoes_insumos_receitas as $aplicacao) {
            $receitas_registradas[] = $aplicacao->receita_laboratorial_id;
        }

        foreach ($aplicacao_insumo->aplicacoes_insumos_produtos as $aplicacao) {
            $produtos_registrados[] = $aplicacao->produto_id;
        }

        $receitas_laboratoriais = ReceitasLaboratoriais::where('receita_laboratorial_tipo_id', 4) // Insumos para manejo
        ->whereNotIn('id', $receitas_registradas)
        ->orderBy('nome')
        ->get();

        $produtos = VwEstoqueDisponivel::where('filial_id', session('_filial')->id)
        ->whereNotIn('produto_id', $produtos_registrados)
        ->where('em_estoque', '>', 0)
        ->where('produto_tipo_id', '<>', 2) // Rações
        ->where('produto_tipo_id', '<>', 5) // Pós-larvas
        ->orderBy('produto_nome')
        ->get();

        return view('admin.aplicacoes_insumos_itens.list')
        ->with('aplicacao_insumo',       $aplicacao_insumo)
        ->with('receitas_laboratoriais', $receitas_laboratoriais)
        ->with('produtos',               $produtos);
    }
}
