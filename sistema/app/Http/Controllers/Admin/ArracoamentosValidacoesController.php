<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Arracoamentos;
use App\Models\VwArracoamentos;
use App\Models\ArracoamentosAplicacoesTipos;
use App\Models\ArracoamentosAplicacoesProdutos;
use App\Models\ArracoamentosAplicacoesReceitas;
use App\Models\ReceitasLaboratoriaisPeriodos;
use App\Models\ReceitasLaboratoriaisProdutos;
use App\Models\EstoqueSaidas;
use App\Models\EstoqueSaidasArracoamentos;
use App\Models\EstoqueEstornos;
use App\Models\EstoqueEstornosJustificativas;
use App\Models\Setores;
use App\Models\Tanques;
use App\Http\Controllers\Admin\ArracoamentosAplicacoesController;
use App\Http\Controllers\Util\DataPolisher;

class ArracoamentosValidacoesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingArracoamentosValidacoes(Request $request)
    {
        $data = $request->only(['data_aplicacao', 'setor_id']);

        $searchParam = [
            'filial_id' => session('_filial')->id,
        ];

        $listView = view('admin.arracoamentos.validacoes.list');

        if (isset($data['data_aplicacao'])) {
            $searchParam['data_aplicacao'] = $data['data_aplicacao'];
            $listView->with('data_aplicacao', $data['data_aplicacao']);
        }

        if (isset($data['setor_id'])) {
            $searchParam['setor_id'] = $data['setor_id'];
            $listView->with('setor_id', $data['setor_id']);
        }

        $arracoamentos = VwArracoamentos::listing($this->rowsPerPage, $searchParam);

        $setores = Setores::whereIn('id', 
            Tanques::where('filial_id', session('_filial')->id)
            ->where('tanque_tipo_id', 1)   // Viveiro de camarões
            ->orWhere('tanque_tipo_id', 6) // Viveiro de peixes
            ->groupBy('setor_id')
            ->get(['setor_id']))
        ->orderBy('nome', 'desc')
        ->get();

        return $listView
        ->with('formData',      $data)
        ->with('arracoamentos', $arracoamentos)
        ->with('setores',       $setores);
    }

    public function createArracoamentosValidacoes(int $arracoamento_id)
    {
        $arracoamento = Arracoamentos::find($arracoamento_id);

        $arracoamentos_horarios = $arracoamento->horarios->sortBy('ordinal');

        $arracoamentos_aplicacoes_tipos = ArracoamentosAplicacoesTipos::orderBy('nome')->get();
        $arracoamentos_aplicacoes_itens = new ArracoamentosAplicacoesController;

        return view('admin.arracoamentos.validacoes.create')
        ->with('arracoamento',                   $arracoamento)
        ->with('arracoamentos_horarios',         $arracoamentos_horarios)
        ->with('arracoamentos_aplicacoes_tipos', $arracoamentos_aplicacoes_tipos)
        ->with('arracoamentos_aplicacoes_itens', $arracoamentos_aplicacoes_itens);
    }

    public function closeArracoamentosValidacoes(int $arracoamento_id)
    {
        $arracoamento = Arracoamentos::find($arracoamento_id);

        $dias_cultivo = $arracoamento->ciclo->dias_cultivo($arracoamento->data_aplicacao);

        $redirect = redirect()->back();

        $produtos = [];

        foreach ($arracoamento->aplicacoes as $aplicacao) {

            $aplicacao_receita = $aplicacao->arracoamento_aplicacao_receita;
            $aplicacao_produto = $aplicacao->arracoamento_aplicacao_produto;

            if ($aplicacao_receita instanceof ArracoamentosAplicacoesReceitas) {

                $receita_periodo = $aplicacao_receita->receita_laboratorial->receitaPorDiasDeCultivo($dias_cultivo);

                if (! $receita_periodo instanceof ReceitasLaboratoriaisPeriodos) {
                    return $redirect->with('error', "A receita {$aplicacao_receita->nome}, não possui um período de utilização definido.");
                }

                $receita_produtos = $receita_periodo->receita_laboratorial_produtos;

                if ($receita_produtos instanceof ReceitasLaboratoriaisProdutos) {
                    return $redirect->with('error', "A receita {$aplicacao_receita->nome}, não possui produtos definidos.");
                }

                foreach ($receita_produtos as $receita_produto) {

                    $produtos[] = [
                        'quantidade' => ($receita_produto->quantidade * $aplicacao_receita->qtd_aplicada()),
                        'produto_id' => $receita_produto->produto_id, 
                    ];

                }

            }

            if ($aplicacao_produto instanceof ArracoamentosAplicacoesProdutos) {
                
                $produtos[] = [
                    'quantidade' => $aplicacao_produto->qtd_aplicada(),
                    'produto_id' => $aplicacao_produto->produto_id, 
                ];

            }

        }

        $saida = EstoqueSaidas::registrarSaida(EstoqueSaidasArracoamentos::class, $produtos, [
            'tipo_destino'    => 3, // Saídas para arraçoamento
            'data_movimento'  => $arracoamento->data_aplicacao,
            'tanque_id'       => $arracoamento->tanque_id,
            'ciclo_id'        => $arracoamento->ciclo_id,
            'arracoamento_id' => $arracoamento->id,
        ]);

        $msg_type = 'error';
        $arracoamento->situacao = 'B'; // Validação bloqueda

        if (key($saida) == 1) { // Registro de saída bem sucedido
            $msg_type = 'success';
            $arracoamento->situacao = 'V'; // Validada
        }

        if (key($saida) == 2) { // Produto indisponível para uso
            $msg_type = 'warning';
            $arracoamento->situacao = 'P'; // Parcialmente validada
        }

        $arracoamento->usuario_id = auth()->user()->id;
        $arracoamento->save();

        return $redirect->with($msg_type, current($saida));
    }

    public function reverseArracoamentosValidacoes(Request $request, int $arracoamento_id)
    {
        $rules = [
            'descricao' => 'required',
        ];

        $messages = [
            'descricao.required' => 'Informe uma justificativa para realizar o estorno.',
        ];

        $data = $this->validate($request, $rules, $messages);

        $data = DataPolisher::toPolish($data);

        $arracoamento = Arracoamentos::find($arracoamento_id);

        $redirect = redirect()->back();

        if ($arracoamento instanceof Arracoamentos) {

            $justificativa = EstoqueEstornosJustificativas::create([
                'tipo_origem' => 3, // Estorno de arraçoamento
                'descricao'   => $data['descricao'],
                'filial_id'   => session('_filial')->id,
                'usuario_id'  => auth()->user()->id,
            ]);

            if ($justificativa instanceof EstoqueEstornosJustificativas) {

                $saidas_arracoamento = $arracoamento->estoque_saidas_arracoamentos;

                $estorno = EstoqueEstornos::registrarEstorno($saidas_arracoamento, [
                    'tipo_destino'   => $justificativa->tipo_origem,
                    'data_movimento' => $arracoamento->data_aplicacao,
                    'estorno_justificativa_id' => $justificativa->id,
                ]);

                $msg_type = 'error';
                $arracoamento->situacao = 'B'; // Validação bloqueda

                if (key($estorno) == 2) { // Estornos registrados com sucesso
                    $msg_type = 'success';
                    $arracoamento->situacao = 'N'; // Aplicações não validadas
                }

                $arracoamento->usuario_id = auth()->user()->id;
                $arracoamento->save();

                return $redirect->with($msg_type, current($estorno));

            }

            return $redirect->with('error', 'Não foi possível realizar o estorno. Justificativa não salva.');

        }

        return $redirect->with('error', 'Não foi possível realizar o estorno. Arraçoamento não encontrado.');
    }
}
