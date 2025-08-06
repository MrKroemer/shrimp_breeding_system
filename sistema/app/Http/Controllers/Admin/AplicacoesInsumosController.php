<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwAplicacoesInsumos;
use App\Models\AplicacoesInsumos;
use App\Models\AplicacoesInsumosGrupos;
use App\Models\AplicacoesInsumosGruposObservacoes;
use App\Models\AplicacoesInsumosReceitas;
use App\Models\AplicacoesInsumosProdutos;
use App\Models\ReceitasLaboratoriais;
use App\Models\VwEstoqueDisponivel;
use Carbon\Carbon;
use Validator;

class AplicacoesInsumosController extends Controller
{
    public function viewAplicacoesInsumos(Request $request, int $aplicacao_insumo_grupo_id)
    {
        return $this->createAplicacoesInsumos($request, $aplicacao_insumo_grupo_id);
    }

    public function createAplicacoesInsumos(Request $request, int $aplicacao_insumo_grupo_id)
    {
        $data = $request->only(['data_aplicacao']);

        if (! isset($data['data_aplicacao'])) {
            $data['data_aplicacao'] = date('d/m/Y');
        }

        $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao'])->format('Y-m-d');

        $aplicacao_insumo_grupo = AplicacoesInsumosGrupos::find($aplicacao_insumo_grupo_id);

        $aplicacoes_insumos = VwAplicacoesInsumos::listing([
            'data_aplicacao' => $data['data_aplicacao'],
            'filial_id'      => session('_filial')->id,
            'aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo->id,
        ]);

        $receitas_laboratoriais = ReceitasLaboratoriais::ativos()
        ->where('receita_laboratorial_tipo_id', 4) // INSUMOS PARA MANEJO
        ->where('filial_id', session('_filial')->id) 
        ->orderBy('nome')
        ->get();

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->where('em_estoque', '>', 0)
        ->where('produto_tipo_id', '<>', 2) // RAÇÕES
        ->where('produto_tipo_id', '<>', 5) // PÓS LARVAS
        ->orderBy('produto_nome')
        ->get();

        $aplicacao_observacoes = $aplicacao_insumo_grupo->observacoes
        ->where('data_aplicacao', $data_aplicacao);

        $observacoes = '';

        if ($aplicacao_observacoes->first()) {
            $observacoes = $aplicacao_observacoes->first()->observacoes;
        }

        return view('admin.aplicacoes_insumos_grupos.registro_aplicacoes.create')
        ->with('data_aplicacao',         $data['data_aplicacao'])
        ->with('aplicacoes_insumos',     $aplicacoes_insumos)
        ->with('aplicacao_insumo_grupo', $aplicacao_insumo_grupo)
        ->with('receitas_laboratoriais', $receitas_laboratoriais)
        ->with('produtos',               $produtos)
        ->with('observacoes',            $observacoes);
    }

    public function storeAplicacoesInsumos(Request $request, int $aplicacao_insumo_grupo_id)
    {
        $data = $request->except(['_token']);

        $data['usuario_id'] = auth()->user()->id;

        $aplicacao_insumo_grupo = AplicacoesInsumosGrupos::find($aplicacao_insumo_grupo_id);

        $rules = [
            'data_aplicacao' => 'required',
        ];

        $messages = [
            'data_aplicacao.required' => 'A data da aplicação deve ser informada.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        $redirect = redirect()->route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_view', 
            ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id, 'data_aplicacao' => $data['data_aplicacao']]
        );

        $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao'])->format('Y-m-d');

        if ($validator->fails()) {
            return $redirect->withErrors($validator)->withInput();
        }

        if (is_null($data['receita_laboratorial_id']) && is_null($data['produto_id'])) {
            return $redirect->with('error', 'Informe uma receita ou produto a ser aplicado');
        }

        if (is_null($data['qtd_receita']) && is_null($data['qtd_produto'])) {
            return $redirect->with('error', 'Informe a quantidade a ser aplicada');
        }
        
        $warnings = [];

        foreach ($data as $key => $value) {
            
            if (strpos($key, 'tanque_') === 0) {

                $tanque_id = str_replace('tanque_', '', $key);

                $aplicacoes_insumos = $aplicacao_insumo_grupo->aplicacoes
                ->where('tanque_id', $tanque_id)
                ->where('data_aplicacao', $data_aplicacao)
                ->sortByDesc('id');

                if ($aplicacoes_insumos->isEmpty()) {
                    
                    $aplicacao = AplicacoesInsumos::create([
                        'data_aplicacao'            => $data['data_aplicacao'],
                        'aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id,
                        'tanque_id'                 => $tanque_id,
                        'filial_id'                 => session('_filial')->id,
                        'usuario_id'                => $data['usuario_id'],
                    ]);

                    if (! $aplicacao instanceof AplicacoesInsumos) {
                        return $redirect->with('error', 'Ocorreu um erro! Não foi possível registrar todos os tanques selecionados');
                    }

                } else {
                    $aplicacao = $aplicacoes_insumos->first();
                }

                /**
                 * Devido o framework não retornar a coluna 'situacao' ao criar o registro,
                 * o acesso ao atributo 'situacao' é tratado como uma chamada à função 'situacao()', ocasionando
                 * o erro 'LogicException App\Models\AplicacoesInsumos::situacao must return a relationship instance.'.
                 * Como solução, o registro deve ser consultado novamente.
                 */
                $aplicacao = AplicacoesInsumos::find($aplicacao->id);

                if ($aplicacao->situacao == 'N') {

                    $data['aplicacao_insumo_id'] = $aplicacao->id;

                    if ($data['receita_laboratorial_id'] > 0) {

                        $aplicacao_receita = AplicacoesInsumosReceitas::where('aplicacao_insumo_id', $data['aplicacao_insumo_id'])
                        ->where('receita_laboratorial_id', $data['receita_laboratorial_id'])
                        ->get();

                        if ($aplicacao_receita->isEmpty()) {

                            $data['quantidade'] = $data['qtd_receita'] ?: 0;

                            $aplicacao_receita = AplicacoesInsumosReceitas::create($data);

                            if (! $aplicacao_receita instanceof AplicacoesInsumosReceitas) {
                                return $redirect->with('error', 'Ocorreu um erro! Não foi possível registrar a receita para todos os tanques selecionados');
                            }

                        }

                    }

                    if ($data['produto_id'] > 0) {

                        $aplicacao_produto = AplicacoesInsumosProdutos::where('aplicacao_insumo_id', $data['aplicacao_insumo_id'])
                        ->where('produto_id', $data['produto_id'])
                        ->get();

                        if ($aplicacao_produto->isEmpty()) {

                            $data['quantidade'] = $data['qtd_produto'] ?: 0;

                            $aplicacao_produto = AplicacoesInsumosProdutos::create($data);

                            if (! $aplicacao_produto instanceof AplicacoesInsumosProdutos) {
                                return $redirect->with('error', 'Ocorreu um erro! Não foi possível registrar o produto para todos os tanques selecionados');
                            }

                        }

                    }
                
                } else {

                    $warnings[] = "Devido a aplicação estar validada, nenhum item pode ser adicionado para o tanque {$aplicacao->tanque->sigla} em {$data['data_aplicacao']}.";

                }

            }

        }

        $redirect->with('warning', $warnings);

        return $redirect->with('success', 'Aplicações de insumos cadastradas com sucesso!');
    }

    public function updateAplicacoesInsumos(Request $request, int $aplicacao_insumo_grupo_id, int $id)
    {
        $data = $request->except(['_token']);

        $data['usuario_id'] = auth()->user()->id;

        if (! isset($data['data_aplicacao'])) {
            $data['data_aplicacao'] = date('d/m/Y');
        }

        $aplicacao_insumo = AplicacoesInsumos::find($id);

        $redirect = redirect()->route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_view', 
            ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id, 'data_aplicacao' => $data['data_aplicacao']]
        );

        unset($data['data_aplicacao']);

        if ($aplicacao_insumo instanceof AplicacoesInsumos) {

            if ($aplicacao_insumo->update($data)) {
                return $redirect;
            }

        }

        return $redirect->with('error', 'Não foi possível registrar as observações para o tanque! Tente novamente.');
    }

    public function removeAplicacoesInsumos(Request $request, int $aplicacao_insumo_grupo_id, int $id)
    {
        $data = $request->only(['data_aplicacao']);

        if (! isset($data['data_aplicacao'])) {
            $data['data_aplicacao'] = date('d/m/Y');
        }

        $aplicacao_insumo = AplicacoesInsumos::find($id);

        $redirect = redirect()->route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_view', 
            ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id, 'data_aplicacao' => $data['data_aplicacao']]
        );

        if ($aplicacao_insumo->situacao == 'N') {
            
            if ($aplicacao_insumo->delete()) {
                return $redirect->with('success', 'Registro excluido com sucesso!');
            }

        }

        return $redirect->with('error', 'Ocorreu um erro durante a tentativa de exclusão do registro! Tente novamente.');
    }

    public function storeAplicacoesInsumosGruposObservacoes(Request $request, int $aplicacao_insumo_grupo_id)
    {
        $data = $request->except('_token');

        $data['aplicacao_insumo_grupo_id'] = $aplicacao_insumo_grupo_id;
        $data['usuario_id'] = auth()->user()->id;

        $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao'])->format('Y-m-d');

        $observacoes = AplicacoesInsumosGrupos::find($aplicacao_insumo_grupo_id)->observacoes
        ->where('data_aplicacao', $data_aplicacao);

        $redirect = redirect()->route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_view', 
            ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo_id, 'data_aplicacao' => $data['data_aplicacao']]
        );

        if ($observacoes->isEmpty()) {

            $observacoes = AplicacoesInsumosGruposObservacoes::create($data);

            if ($observacoes instanceof AplicacoesInsumosGruposObservacoes) {
                return $redirect;
            }

        } else if ($observacoes->count() == 1) {

            $observacoes = $observacoes->first();

            if ($observacoes->update($data)) {
                return $redirect;
            }

        }

        return $redirect->with('error', 'Não foi possível registrar as observações gerais! Tente novamente.');
    }
}
