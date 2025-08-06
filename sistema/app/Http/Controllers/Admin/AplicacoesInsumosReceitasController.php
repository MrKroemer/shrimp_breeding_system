<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AplicacoesInsumosReceitas;

class AplicacoesInsumosReceitasController extends Controller
{
    public function updateAplicacoesInsumosReceitas(Request $request, int $aplicacao_insumo_id, int $id)
    {
        $data = $request->except(['_token']);

        $aplicacao_insumo = AplicacoesInsumosReceitas::find($id);

        $aplicacao_insumo->quantidade = $data['quantidade'];

        if ($aplicacao_insumo->update()) {
            return redirect()->back()
            ->with('success', 'Quantidade da receita atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Não foi possível atualizar a quantidade da receita.');
    }

    public function removeAplicacoesInsumosReceitas(Request $request, int $aplicacao_insumo_grupo_id, int $id)
    {
        $data = $request->only(['data_aplicacao']);

        $redirect = redirect()->route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_view', 
            ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id, 'data_aplicacao' => $data['data_aplicacao']]
        );

        if (isset($data['data_aplicacao'])) {
            $redirect->with('data_aplicacao', $data['data_aplicacao']);
        }

        $aplicacao_insumo = AplicacoesInsumosReceitas::find($id);

        if ($aplicacao_insumo->delete()) {
            return $redirect->with('success', 'Receita removida com sucesso!');
        }

        return $redirect->with('error', 'Não foi possível remover a receita.');
    }
}
