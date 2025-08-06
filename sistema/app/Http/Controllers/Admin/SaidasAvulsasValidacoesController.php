<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwEstoqueDisponivel;
use App\Models\SaidasAvulsas;
use App\Models\EstoqueSaidas;
use App\Models\ReceitasLaboratoriais;
use App\Models\ReceitasLaboratoriaisPeriodos;
use App\Models\ReceitasLaboratoriaisProdutos;
use App\Models\EstoqueSaidasAvulsas;
use App\Models\EstoqueEstornos;
use App\Models\EstoqueEstornosJustificativas;
use App\Models\Tanques;
use App\Http\Controllers\Util\DataPolisher;

class SaidasAvulsasValidacoesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingSaidasAvulsasValidacoes(Request $request)
    {
        $data = $request->except(['_token']);

        $searchParam = [
            'filial_id' => session('_filial')->id,
        ];

        $listView = view('admin.saidas_avulsas.validacoes.list');

        if (isset($data['tanque_id'])) {
            $searchParam['tanque_id'] = $data['tanque_id'];
            $listView->with('tanque_id', $data['tanque_id']);
        }

        if (isset($data['data_inicial'])) {
            $searchParam['data_inicial'] = $data['data_inicial'];
            $listView->with('data_inicial', $data['data_inicial']);
        }

        if (isset($data['data_final'])) {
            $searchParam['data_final'] = $data['data_final'];
            $listView->with('data_final', $data['data_final']);
        }

        $saidas_avulsas = SaidasAvulsas::listing($this->rowsPerPage, $searchParam);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->whereNotIn('tanque_tipo_id', [2, 5]) // Berçarios e recirculadores
        ->orderBy('sigla', 'asc')
        ->get();

        return $listView
        ->with('formData',       $data)
        ->with('saidas_avulsas', $saidas_avulsas)
        ->with('tanques',        $tanques);
    }

    public function createSaidasAvulsasValidacoes(Request $request, int $saida_avulsa_id)
    {
        $data = $request->all();

        $createView = view('admin.saidas_avulsas.validacoes.create');
/* 
        if (isset($data['tanque_id'])) {
            $createView->with('tanque_id', $data['tanque_id']);
        }

        if (isset($data['data_inicial'])) {
            $createView->with('data_inicial', $data['data_inicial']);
        }

        if (isset($data['data_final'])) {
            $createView->with('data_final', $data['data_final']);
        }
 */
        $saida_avulsa = SaidasAvulsas::find($saida_avulsa_id);

        $receitas_laboratoriais = ReceitasLaboratoriais::where('receita_laboratorial_tipo_id', 4) // Insumos para manejo
        ->orderBy('nome')
        ->get();

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->get();

        return $createView
        ->with('saida_avulsa',               $saida_avulsa)
        ->with('receitas_laboratoriais',     $receitas_laboratoriais)
        ->with('produtos',                   $produtos);
    }

    public function closeSaidasAvulsasValidacoes(int $saida_avulsa_id)
    {
        $saida_avulsa = SaidasAvulsas::find($saida_avulsa_id);

        $redirect = redirect()->back();

        $produtos = [];

        foreach ($saida_avulsa->saidas_avulsas_receitas as $receita) {

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

        foreach ($saida_avulsa->saidas_avulsas_produtos as $produto) {

            $produtos[] = [
                'quantidade' => $produto->quantidade,
                'produto_id' => $produto->produto_id,
            ];

        }

        $saida = EstoqueSaidas::registrarSaida(EstoqueSaidasAvulsas::class, $produtos, [
            'tipo_destino'    => 5, // Saídas avulsas
            'data_movimento'  => $saida_avulsa->data_movimento,
            'tanque_id'       => $saida_avulsa->tanque_id,
            'saida_avulsa_id' => $saida_avulsa->id,
        ]);

        $msg_type = 'error';
        $saida_avulsa->situacao = 'B'; // Validação bloqueda

        if (key($saida) == 1) { // Registro de saída bem sucedido
            $msg_type = 'success';
            $saida_avulsa->situacao = 'V'; // Validada
        }

        if (key($saida) == 2) { // Produto indisponível para uso
            $msg_type = 'warning';
            $saida_avulsa->situacao = 'P'; // Parcialmente validada
        }

        $saida_avulsa->usuario_id = auth()->user()->id;
        $saida_avulsa->save();

        return $redirect->with($msg_type, current($saida));
    }

    public function reverseSaidasAvulsasValidacoes(Request $request, int $saida_avulsa_id)
    {
        $rules = [
            'descricao' => 'required',
        ];

        $messages = [
            'descricao.required' => 'Informe uma justificativa para realizar o estorno.',
        ];

        $data = $this->validate($request, $rules, $messages);

        $data = DataPolisher::toPolish($data);

        $saida_avulsa = SaidasAvulsas::find($saida_avulsa_id);

        $redirect = redirect()->back();

        if ($saida_avulsa instanceof SaidasAvulsas) {

            $justificativa = EstoqueEstornosJustificativas::create([
                'tipo_origem' => 5, // Estorno de saída avulsa
                'descricao'   => $data['descricao'],
                'filial_id'   => session('_filial')->id,
                'usuario_id'  => auth()->user()->id,
            ]);

            if ($justificativa instanceof EstoqueEstornosJustificativas) {

                $saidas_avulsa = $saida_avulsa->estoque_saidas_avulsas;

                $estorno = EstoqueEstornos::registrarEstorno($saidas_avulsa, [
                    'tipo_destino'   => $justificativa->tipo_origem,
                    'data_movimento' => $saida_avulsa->data_movimento,
                    'estorno_justificativa_id' => $justificativa->id,
                ]);

                $msg_type = 'error';
                $saida_avulsa->situacao = 'B'; // Validação bloqueda

                if (key($estorno) == 2) { // Estornos registrados com sucesso
                    $msg_type = 'success';
                    $saida_avulsa->situacao = 'N'; // Aplicações não validadas
                }

                $saida_avulsa->usuario_id = auth()->user()->id;
                $saida_avulsa->save();

                return $redirect->with($msg_type, current($estorno));

            }

            return $redirect->with('error', 'Não foi possível realizar o estorno. Justificativa não salva.');

        }

        return $redirect->with('error', 'Não foi possível realizar o estorno. Produtos não encontrada.');
    }
}
