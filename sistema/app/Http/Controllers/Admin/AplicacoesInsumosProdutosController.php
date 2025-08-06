<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AplicacoesInsumosProdutos;

class AplicacoesInsumosProdutosController extends Controller
{
    public function updateAplicacoesInsumosProdutos(Request $request, int $aplicacao_insumo_id, int $id)
    {
        $data = $request->except(['_token']);

        $aplicacao_insumo = AplicacoesInsumosProdutos::find($id);

        $aplicacao_insumo->quantidade = $data['quantidade'];

        if ($aplicacao_insumo->update()) {
            return redirect()->back()
            ->with('success', 'Quantidade do produto atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Não foi possível atualizar a quantidade do produto.');
    }

    public function removeAplicacoesInsumosProdutos(Request $request, int $aplicacao_insumo_grupo_id, int $id)
    {
        $data = $request->only(['data_aplicacao']);

        $redirect = redirect()->route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_view', 
            ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id, 'data_aplicacao' => $data['data_aplicacao']]
        );

        if (isset($data['data_aplicacao'])) {
            $redirect->with('data_aplicacao', $data['data_aplicacao']);
        }

        $aplicacao_insumo = AplicacoesInsumosProdutos::find($id);

        if ($aplicacao_insumo->delete()) {
            return redirect()->back()
            ->with('success', 'Produto removido com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Não foi possível remover o produto.');
    }
}
