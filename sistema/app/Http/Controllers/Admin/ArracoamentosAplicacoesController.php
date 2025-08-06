<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Arracoamentos;
use App\Models\ArracoamentosHorarios;
use App\Models\ArracoamentosAplicacoes;
use App\Models\ReceitasLaboratoriais;
use App\Models\VwEstoqueDisponivel;
use App\Models\ArracoamentosAplicacoesProdutos;
use App\Models\ArracoamentosAplicacoesReceitas;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Requests\ArracoamentosAplicacoesCreateFormRequest;
use App\Http\Resources\ArracoamentosAplicacoesItensJsonResource;

class ArracoamentosAplicacoesController extends Controller
{
    public function storeArracoamentosAplicacoes(Request $request, int $arracoamento_id, int $arracoamento_horario_id)
    {
        $horario = ArracoamentosHorarios::find($arracoamento_horario_id);

        if ($horario instanceof ArracoamentosHorarios) {

            $rules = [
                "add_arracoamento_aplicacao_tipo_id_{$horario->id}" => 'required',
                "add_arracoamento_aplicacao_item_id_{$horario->id}" => 'required',
                "add_quantidade_{$horario->id}"                     => 'required',
            ];

            $messages = [
                "add_arracoamento_aplicacao_tipo_id_{$horario->id}.required" => 'O campo "Aplicação de" deve ser informado.',
                "add_arracoamento_aplicacao_item_id_{$horario->id}.required" => 'O campo "Item" deve ser informado.',
                "add_quantidade_{$horario->id}.required"                     => 'O campo "Quantidade" deve ser informado.',
            ];

            $data = $this->validate($request, $rules, $messages);

            $data = DataPolisher::toPolish($data);

            $aplicacao = ArracoamentosAplicacoes::create([
                'arracoamento_id'                => $arracoamento_id, 
                'arracoamento_horario_id'        => $arracoamento_horario_id, 
                'arracoamento_aplicacao_tipo_id' => $data["add_arracoamento_aplicacao_tipo_id_{$horario->id}"], 
            ]);

            if ($aplicacao instanceof ArracoamentosAplicacoes) {

                if ($aplicacao->criarArracoamentoAplicacao($data)) {
                    return redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id]);
                }

            }

        }

        return redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id])
        ->with('error', 'Ocorreu um erro ao salvar o item. Tente novamente!');
    }

    public function updateArracoamentosAplicacoes(Request $request, int $arracoamento_id, int $arracoamento_horario_id, int $id)
    {
        $aplicacao = ArracoamentosAplicacoes::find($id);

        if ($aplicacao instanceof ArracoamentosAplicacoes) {

            $type = $request->get('_type');

            $rules = [
                "edit_arracoamento_aplicacao_tipo_id_{$aplicacao->id}" => 'required',
                "edit_arracoamento_aplicacao_item_id_{$aplicacao->id}" => 'required',
                "edit_quantidade_{$aplicacao->id}"                     => 'required',
            ];

            $messages = [
                "edit_arracoamento_aplicacao_tipo_id_{$aplicacao->id}.required" => 'O campo "Aplicação de" deve ser informado.',
                "edit_arracoamento_aplicacao_item_id_{$aplicacao->id}.required" => 'O campo "Item" deve ser informado.',
                "edit_quantidade_{$aplicacao->id}.required"                     => 'O campo "Quantidade" deve ser informado.',
            ];

            $data = $this->validate($request, $rules, $messages);

            $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

            if ($aplicacao->alterarArracoamentoAplicacao($data, $type)) {
                return redirect()->back()->with('arracoamento_id', $arracoamento_id);
            }

        }

        return redirect()->back()->with('arracoamento_id', $arracoamento_id)
        ->with('error', 'Ocorreu um erro ao salvar o item. Tente novamente!');
    }

    public function removeArracoamentosAplicacoes(int $arracoamento_id, int $arracoamento_horario_id, int $id)
    {
        $aplicacao = ArracoamentosAplicacoes::find($id);

        if ($aplicacao instanceof ArracoamentosAplicacoes) {

            if (! empty($aplicacao->arracoamento_aplicacao_produto)) {
                $aplicacao->arracoamento_aplicacao_produto->delete();
            }

            if (! empty($aplicacao->arracoamento_aplicacao_receita)) {
                $aplicacao->arracoamento_aplicacao_receita->delete();
            }

            if ($aplicacao->delete()) {
                return redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id]);
            }

        }

        return redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id])
        ->with('error', 'Ocorreu um erro ao excluir o item. Tente novamente!');
    }

    public function getJsonItens(int $arracoamento_aplicacao_tipo_id)
    {
        if (is_numeric($arracoamento_aplicacao_tipo_id) && $arracoamento_aplicacao_tipo_id > 0) {

            switch ($arracoamento_aplicacao_tipo_id) {

                case 1: // RAÇÂO

                    $aplicacoes_itens = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
                    ->orderBy('vw_estoque_disponivel.produto_nome')
                    ->join('unidades_medidas', 'unidades_medidas.id', '=', 'vw_estoque_disponivel.produto_und_sai_id')
                    ->where('vw_estoque_disponivel.em_estoque', '>', 0)
                    ->where('vw_estoque_disponivel.produto_tipo_id', 2) // RAÇÂO
                    ->get([
                        'vw_estoque_disponivel.produto_id AS id', 
                        'vw_estoque_disponivel.produto_nome AS nome',
                        'unidades_medidas.sigla AS unidade_medida',
                    ]);

                    break;

                case 2: // PROBIÓTICO

                    $aplicacoes_itens = ReceitasLaboratoriais::orderBy('receitas_laboratoriais.nome')
                    ->where('receitas_laboratoriais.receita_laboratorial_tipo_id', 1)
                    ->join('unidades_medidas', 'unidades_medidas.id', '=', 'receitas_laboratoriais.unidade_medida_id')
                    ->get([
                        'receitas_laboratoriais.id AS id', 
                        'receitas_laboratoriais.nome AS nome', 
                        'unidades_medidas.sigla AS unidade_medida', 
                    ]);

                    break;

                case 3: // ADITIVO

                    $aplicacoes_itens = ReceitasLaboratoriais::orderBy('receitas_laboratoriais.nome')
                    ->where('receitas_laboratoriais.receita_laboratorial_tipo_id', 2)
                    ->join('unidades_medidas', 'unidades_medidas.id', '=', 'receitas_laboratoriais.unidade_medida_id')
                    ->get([
                        'receitas_laboratoriais.id AS id', 
                        'receitas_laboratoriais.nome AS nome', 
                        'unidades_medidas.sigla AS unidade_medida', 
                    ]);

                    break;

                case 4: // VITAMINAS

                    $aplicacoes_itens = ReceitasLaboratoriais::orderBy('receitas_laboratoriais.nome')
                    ->where('receitas_laboratoriais.receita_laboratorial_tipo_id', 3)
                    ->join('unidades_medidas', 'unidades_medidas.id', '=', 'receitas_laboratoriais.unidade_medida_id')
                    ->get([
                        'receitas_laboratoriais.id AS id', 
                        'receitas_laboratoriais.nome AS nome', 
                        'unidades_medidas.sigla AS unidade_medida', 
                    ]);

                    break;

                case 5: // OUTROS INSUMOS

                    $aplicacoes_itens = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
                    ->orderBy('vw_estoque_disponivel.produto_nome')
                    ->join('unidades_medidas', 'unidades_medidas.id', '=', 'vw_estoque_disponivel.produto_und_sai_id')
                    ->where('vw_estoque_disponivel.em_estoque', '>', 0)
                    ->where(function ($query) {
                        $query->where('vw_estoque_disponivel.produto_tipo_id', 1) // INSUMOS
                            ->orWhere('vw_estoque_disponivel.produto_tipo_id', 4); // OUTROS
                    })->get([
                        'vw_estoque_disponivel.produto_id AS id', 
                        'vw_estoque_disponivel.produto_nome AS nome',
                        'unidades_medidas.sigla AS unidade_medida',
                    ]);

                    break;

            }

            if (! empty($aplicacoes_itens)) {
                return ArracoamentosAplicacoesItensJsonResource::collection($aplicacoes_itens);
            }
            
        }

        return null;
    }
}
