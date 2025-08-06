<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwEstoqueDisponivel;
use App\Models\Preparacoes;
use App\Models\PreparacoesTipos;
use App\Models\EstoqueSaidas;
use App\Models\EstoqueSaidasPreparacoes;
use App\Models\EstoqueEstornos;
use App\Models\EstoqueEstornosJustificativas;
use App\Http\Controllers\Util\DataPolisher;

class PreparacoesV2ValidacoesController extends Controller
{
    public function listingPreparacoesValidacoes(int $preparacao_id)
    {
        $preparacao = Preparacoes::find($preparacao_id);

        $preparacoes_tipos = PreparacoesTipos::all();

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->where('em_estoque', '>', 0)
        ->whereIn('produto_tipo_id', [1, 4]) // INSUMOS, OUTROS
        ->get();

        $cicloSituacao = $preparacao->ciclo->verificarSituacao([2, 3, 4]);

        return view('admin.preparacoes_v2.validacoes.list')
        ->with('preparacao',        $preparacao)
        ->with('preparacoes_tipos', $preparacoes_tipos)
        ->with('produtos',          $produtos)
        ->with('cicloSituacao',     $cicloSituacao);
    }

    public function closePreparacoesValidacoes(int $preparacao_id)
    {
        $preparacao = Preparacoes::find($preparacao_id);

        if ($preparacao instanceof Preparacoes) {

            foreach ($preparacao->aplicacoes->sortBy('id') as $aplicacao) {

                $saida = EstoqueSaidas::registrarSaida([
                    'tanque_id'      => $preparacao->ciclo->tanque_id,
                    'data_movimento' => $aplicacao->data_aplicacao,
                    'quantidade'     => $aplicacao->quantidade,
                    'produto_id'     => $aplicacao->produto_id,
                    'filial_id'      => session('_filial')->id,
                    'usuario_id'     => auth()->user()->id,
                    'tipo_destino'   => 1, // Saídas para preparações
                ]);

                if ($saida instanceof EstoqueSaidas) {

                    $saida_preparacao = EstoqueSaidasPreparacoes::create([
                        'estoque_saida_id'        => $saida->id,
                        'preparacao_aplicacao_id' => $aplicacao->id,
                        'preparacao_id'           => $preparacao->id,
                        'tanque_id'               => $preparacao->ciclo->tanque_id,
                        'ciclo_id'                => $preparacao->ciclo_id,
                    ]);

                    if ($saida_preparacao instanceof EstoqueSaidasPreparacoes) {
                        continue;
                    }

                }

                $preparacao->situacao = 'P'; // Indica preparação parcialmente validada
                $preparacao->usuario_id = auth()->user()->id;
                $preparacao->save();

                return redirect()->back()
                ->with('preparacao_id', $preparacao_id)
                ->with('error', 'Alguns produtos não foram validados. Por favor, entre em contato com o suporte.');

            }

            $preparacao->situacao = 'V'; // Indica preparação validada
            $preparacao->usuario_id = auth()->user()->id;
            $preparacao->save();

            return redirect()->back()
            ->with('preparacao_id', $preparacao_id)
            ->with('success', 'Preparação validada com sucesso!');
        }

        return redirect()->route('admin.preparacoes_v2')
        ->with('error', 'Não foi possível realizar a validação. Preparação não encontrada.');
    }

    public function reversePreparacoesValidacoes(Request $request, int $preparacao_id)
    {
        $rules = [
            'descricao' => 'required',
        ];

        $messages = [
            'descricao.required' => 'Informe uma justificativa para realizar o estorno.',
        ];

        $data = $this->validate($request, $rules, $messages);

        $data = DataPolisher::toPolish($data);

        $preparacao = Preparacoes::find($preparacao_id);

        if ($preparacao instanceof Preparacoes) {

            $justificativa = EstoqueEstornosJustificativas::create([
                'descricao'   => $data['descricao'],
                'tipo_origem' => 1, // Estorno de preparação
                'filial_id'   => session('_filial')->id,
                'usuario_id'  => auth()->user()->id,
            ]);

            if ($justificativa instanceof EstoqueEstornosJustificativas) {

                $saidas_preparacao = $preparacao->estoque_saidas_preparacoes;

                foreach ($saidas_preparacao as $saida_preparacao) {

                    $saida = $saida_preparacao->estoque_saida;

                    if ($saida->tipo_destino == 1) { // Saídas para preparação

                        $estorno = EstoqueEstornos::create([
                            'quantidade'               => $saida->quantidade,
                            'valor_unitario'           => $saida->valor_unitario,
                            'valor_total'              => $saida->valor_total,
                            'data_movimento'           => $saida->data_movimento,
                            'tipo_origem'              => $saida->tipo_destino,
                            'produto_id'               => $saida->produto_id,
                            'estoque_saida_id'         => $saida->id,
                            'estorno_justificativa_id' => $justificativa->id,
                            'filial_id'                => $saida->filial_id,
                            'usuario_id'               => auth()->user()->id,
                        ]);

                        if ($estorno instanceof EstoqueEstornos) {

                            $saida->tipo_destino = 7; // Saídas estornadas

                            if ($saida->save()) {
                                continue;
                            }

                        }

                        return redirect()->back()
                        ->with('preparacao_id', $preparacao_id)
                        ->with('error', 'Alguns produtos não foram estornados. Por favor, entre em contato com o suporte!');

                    }

                }

                $preparacao->situacao = 'N'; // Indica preparação não validada
                $preparacao->usuario_id = auth()->user()->id;
                $preparacao->save();

                return redirect()->back()
                ->with('preparacao_id', $preparacao_id)
                ->with('success', 'Preparação estornada com sucesso!');

            }

            return redirect()->route('admin.preparacoes_v2')
            ->with('error', 'Não foi possível realizar o estorno. Justificativa não salva.');

        }
        
        return redirect()->route('admin.preparacoes_v2')
        ->with('error', 'Não foi possível realizar o estorno. Preparação não encontrada.');
    }
}
