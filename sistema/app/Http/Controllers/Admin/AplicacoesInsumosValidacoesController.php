<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ReceitasLaboratoriais;
use App\Models\ReceitasLaboratoriaisPeriodos;
use App\Models\ReceitasLaboratoriaisProdutos;
use App\Models\VwProdutos;
use App\Models\VwEstoqueDisponivel;
use App\Models\VwAplicacoesInsumos;
use App\Models\AplicacoesInsumos;
use App\Models\EstoqueSaidas;
use App\Models\EstoqueSaidasManejos;
use App\Models\EstoqueEstornos;
use App\Models\EstoqueEstornosJustificativas;
use App\Models\AplicacoesInsumosGrupos;
use App\Models\HistoricoExportacoes;
use App\Http\Controllers\Util\DataPolisher;

class AplicacoesInsumosValidacoesController extends Controller
{
    public function listingAplicacoesInsumosValidacoes(Request $request)
    {
        $data = $request->only(['data_aplicacao']);

        if (! isset($data['data_aplicacao'])) {
            $data['data_aplicacao'] = date('d/m/Y');
        }

        $aplicacoes_insumos = VwAplicacoesInsumos::listing([
            'data_aplicacao' => $data['data_aplicacao'],
            'filial_id'      => session('_filial')->id,
        ]);

        return view('admin.aplicacoes_insumos_grupos.validacoes.list')
        ->with('data_aplicacao',     $data['data_aplicacao'])
        ->with('aplicacoes_insumos', $aplicacoes_insumos);
    }

    public function closeAplicacoesInsumosValidacoes(int $aplicacao_insumo_id)
    {
        $aplicacao_insumo = AplicacoesInsumos::find($aplicacao_insumo_id);

        $redirect = redirect()->route('admin.aplicacoes_insumos_validacoes', [
            'data_aplicacao' => $aplicacao_insumo->data_aplicacao()
        ]);

        $produtos = [];

        foreach ($aplicacao_insumo->aplicacoes_insumos_receitas as $receita) {

            if ($receita->receita_laboratorial->receita_laboratorial_tipo_id == 4) { // Receitas para manejos

                $receita_periodo = $receita->receita_laboratorial->receita_laboratorial_periodos->sortBy('id')->last();

                if (! $receita_periodo instanceof ReceitasLaboratoriaisPeriodos) {
                    return $redirect->with('error', "A receita {$receita->nome}, não possui um período de utilização definido.");
                }

                $receita_produtos = $receita_periodo->receita_laboratorial_produtos;

                if ($receita_produtos instanceof ReceitasLaboratoriaisProdutos) {
                    return $redirect->with('error', "A receita {$receita->nome}, não possui produtos definidos.");
                }

                foreach ($receita_produtos as $receita_produto) {
    
                    $produtos[] = [
                        'quantidade' => ($receita_produto->quantidade * $receita->quantidade),
                        'produto_id' => $receita_produto->produto_id,
                    ];
    
                }

            }

        }

        foreach ($aplicacao_insumo->aplicacoes_insumos_produtos as $produto) {

            $produtos[] = [
                'quantidade' => $produto->quantidade,
                'produto_id' => $produto->produto_id,
            ];

        }

        $saida = EstoqueSaidas::registrarSaida(EstoqueSaidasManejos::class, $produtos, [
            'tipo_destino'        => 4, // Saídas para manejo
            'data_movimento'      => $aplicacao_insumo->data_aplicacao,
            'tanque_id'           => $aplicacao_insumo->tanque_id,
            'aplicacao_insumo_id' => $aplicacao_insumo->id,
        ]);

        $msg_type = 'error';
        $aplicacao_insumo->situacao = 'B'; // Validação bloqueda

        if (key($saida) == 1) { // Registro de saída bem sucedido
            $msg_type = 'success';
            $aplicacao_insumo->situacao = 'V'; // Validada
        }

        if (key($saida) == 2) { // Produto indisponível para uso
            $msg_type = 'warning';
            $aplicacao_insumo->situacao = 'P'; // Parcialmente validada
        }

        $aplicacao_insumo->usuario_id = auth()->user()->id;
        $aplicacao_insumo->save();

        return $redirect->with($msg_type, current($saida));
    }

    public function reverseAplicacoesInsumosValidacoes(Request $request, int $aplicacao_insumo_id)
    {
        $rules = [
            'descricao' => 'required',
        ];

        $messages = [
            'descricao.required' => 'Informe uma justificativa para realizar o estorno.',
        ];

        $data = $this->validate($request, $rules, $messages);

        $data = DataPolisher::toPolish($data);

        $aplicacao_insumo = AplicacoesInsumos::find($aplicacao_insumo_id);

        $redirect = redirect()->route('admin.aplicacoes_insumos_validacoes', [
            'data_aplicacao' => $aplicacao_insumo->data_aplicacao()
        ]);

        if ($aplicacao_insumo instanceof AplicacoesInsumos) {

            $justificativa = EstoqueEstornosJustificativas::create([
                'tipo_origem' => 4, // Estorno de manejo
                'descricao'   => $data['descricao'],
                'filial_id'   => session('_filial')->id,
                'usuario_id'  => auth()->user()->id,
            ]);

            if ($justificativa instanceof EstoqueEstornosJustificativas) {

                $saidas_manejo = $aplicacao_insumo->estoque_saidas_manejos;

                $estorno = EstoqueEstornos::registrarEstorno($saidas_manejo, [
                    'tipo_destino'   => $justificativa->tipo_origem,
                    'data_movimento' => $aplicacao_insumo->data_aplicacao,
                    'estorno_justificativa_id' => $justificativa->id,
                ]);

                $msg_type = 'error';
                $aplicacao_insumo->situacao = 'B'; // Validação bloqueda

                if (key($estorno) == 2) { // Estornos registrados com sucesso
                    $msg_type = 'success';
                    $aplicacao_insumo->situacao = 'N'; // Aplicações não validadas
                }

                $aplicacao_insumo->usuario_id = auth()->user()->id;
                $aplicacao_insumo->save();

                return $redirect->with($msg_type, current($estorno));

            }

            return $redirect->with('error', 'Não foi possível realizar o estorno. Justificativa não salva.');

        }
        
        return $redirect->with('error', 'Não foi possível realizar o estorno. Aplicação de insumo não encontrada.');
    }
}
