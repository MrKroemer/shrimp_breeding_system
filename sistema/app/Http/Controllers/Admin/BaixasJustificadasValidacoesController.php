<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwEstoqueDisponivel;
use App\Models\BaixasJustificadas;
use App\Models\EstoqueSaidas;
use App\Models\EstoqueSaidasDescartes;
use App\Models\EstoqueEstornos;
use App\Models\EstoqueEstornosJustificativas;
use App\Http\Controllers\Util\DataPolisher;

class BaixasJustificadasValidacoesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingBaixasJustificadasValidacoes(Request $request)
    {
        $data = $request->except(['_token']);

        $searchParam = [
            'filial_id' => session('_filial')->id,
        ];

        $listView = view('admin.baixas_justificadas.validacoes.list');

        if (isset($data['data_inicial'])) {
            $searchParam['data_inicial'] = $data['data_inicial'];
            $listView->with('data_inicial', $data['data_inicial']);
        }

        if (isset($data['data_final'])) {
            $searchParam['data_final'] = $data['data_final'];
            $listView->with('data_final', $data['data_final']);
        }

        $baixas_justificadas = BaixasJustificadas::listing($this->rowsPerPage, $searchParam);

        return $listView
        ->with('formData', $data)
        ->with('baixas_justificadas', $baixas_justificadas);
    }

    public function createBaixasJustificadasValidacoes(Request $request, int $baixa_justificada_id)
    {
        $data = $request->all();

        $createView = view('admin.baixas_justificadas.validacoes.create');
/* 
        if (isset($data['data_inicial'])) {
            $createView->with('data_inicial', $data['data_inicial']);
        }

        if (isset($data['data_final'])) {
            $createView->with('data_final', $data['data_final']);
        }
 */
        $baixa_justificada = BaixasJustificadas::find($baixa_justificada_id);

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->get();

        return $createView
        ->with('baixa_justificada', $baixa_justificada)
        ->with('produtos',          $produtos);
    }

    public function closeBaixasJustificadasValidacoes(int $baixa_justificada_id)
    {
        $baixa_justificada = BaixasJustificadas::find($baixa_justificada_id);

        $produtos = [];

        foreach ($baixa_justificada->baixas_justificadas_produtos as $produto) {

            $produtos[] = [
                'produto_id' => $produto->produto_id,
                'quantidade' => $produto->quantidade,
            ];

        }

        foreach ($produtos as $produto) {

            $saida = EstoqueSaidas::registrarSaida([
                'data_movimento' => $baixa_justificada->data_movimento,
                'quantidade'     => $produto['quantidade'],
                'produto_id'     => $produto['produto_id'],
                'filial_id'      => session('_filial')->id,
                'usuario_id'     => auth()->user()->id,
                'tipo_destino'   => 6, // Saídas de descarte
            ]);

            if ($saida instanceof EstoqueSaidas) {

                $estoque_baixa_justificada = EstoqueSaidasDescartes::create([
                    'estoque_saida_id'      => $saida->id,
                    'baixa_justificada_id'  => $baixa_justificada->id,
                ]);

                if ($estoque_baixa_justificada instanceof EstoqueSaidasDescartes) {
                    continue;
                }

            }

            $baixa_justificada->situacao = 'P'; // Indica parcialmente validada
            $baixa_justificada->usuario_id = auth()->user()->id;
            $baixa_justificada->save();

            return redirect()->back()
            ->with('error', 'Alguns produtos não foram validados. Por favor, entre em contato com o suporte.');

        }

        $baixa_justificada->situacao = 'V'; // Indica validada
        $baixa_justificada->usuario_id = auth()->user()->id;
        $baixa_justificada->save();

        return redirect()->back()
        ->with('success', 'Produtos validados com sucesso!');
    }

    public function reverseBaixasJustificadasValidacoes(Request $request, int $baixa_justificada_id)
    {
        $rules = [
            'descricao' => 'required',
        ];

        $messages = [
            'descricao.required' => 'Informe uma justificativa para realizar o estorno.',
        ];

        $data = $this->validate($request, $rules, $messages);

        $data = DataPolisher::toPolish($data);

        $baixa_justificada = BaixasJustificadas::find($baixa_justificada_id);

        $redirect = redirect()->back();

        if ($baixa_justificada instanceof BaixasJustificadas) {

            $justificativa = EstoqueEstornosJustificativas::create([
                'descricao'   => $data['descricao'],
                'tipo_origem' => 6, // Estorno de descarte
                'filial_id'   => session('_filial')->id,
                'usuario_id'  => auth()->user()->id,
            ]);

            if ($justificativa instanceof EstoqueEstornosJustificativas) {

                $estoque_saidas_descartes = $baixa_justificada->estoque_saidas_descartes;

                foreach ($estoque_saidas_descartes as $estoque_saida_descarte) {

                    $saida = $estoque_saida_descarte->estoque_saida;

                    if ($saida->tipo_destino == 6) { // Saídas de descarte

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

                        return $redirect->with('error', 'Alguns produtos não foram estornados. Por favor, entre em contato com o suporte!');

                    }

                }

                $baixa_justificada->situacao = 'N'; // Indica aplicações não validadas
                $baixa_justificada->usuario_id = auth()->user()->id;
                $baixa_justificada->save();

                return $redirect->with('success', 'Produtos estornados com sucesso!');

            }
            
            return $redirect->with('error', 'Não foi possível realizar o estorno. Justificativa não salva.');

        }
        
        return $redirect->with('error', 'Não foi possível realizar o estorno. Produtos não encontrada.');
    }
}
