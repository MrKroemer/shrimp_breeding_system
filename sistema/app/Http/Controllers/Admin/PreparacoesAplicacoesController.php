<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PreparacoesAplicacoes;
use App\Models\PreparacoesTipos;
use App\Models\Preparacoes;
use App\Models\VwEstoqueDisponivel;
use App\Models\EstoqueSaidas;
use App\Models\EstoqueSaidasPreparacoes;
use App\Models\EstoqueEstornos;
use App\Models\EstoqueEstornosJustificativas;
use App\Http\Requests\PreparacoesAplicacoesCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;

class PreparacoesAplicacoesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingPreparacoesAplicacoes(int $preparacao_id)
    {
        $preparacoes_aplicacoes = PreparacoesAplicacoes::listing($this->rowsPerPage, [
            'preparacao_id' => $preparacao_id
        ]);
        
        $preparacoes_tipos = PreparacoesTipos::all();

        $preparacao = Preparacoes::find($preparacao_id);

        $cicloSituacao = $preparacao->ciclo->verificarSituacao([2, 3, 4]);

        return view('admin.preparacoes.aplicacoes.list')
        ->with('preparacoes_tipos',      $preparacoes_tipos)
        ->with('preparacoes_aplicacoes', $preparacoes_aplicacoes)
        ->with('preparacao',             $preparacao)
        ->with('preparacao_id',          $preparacao_id)
        ->with('cicloSituacao',          $cicloSituacao);
    }

    public function searchPreparacoesAplicacoes(Request $request, int $preparacao_id)
    {
        $formData = $request->except(['_token']);
        $formData['preparacao_id'] = $preparacao_id;

        $preparacoes_aplicacoes = PreparacoesAplicacoes::search($formData, $this->rowsPerPage);

        $preparacoes_tipos = PreparacoesTipos::all();

        $preparacao = Preparacoes::find($preparacao_id);

        $cicloSituacao = $preparacao->ciclo->verificarSituacao([2, 3, 4]);

        return view('admin.preparacoes.aplicacoes.list')
        ->with('formData',               $formData)
        ->with('preparacoes_tipos',      $preparacoes_tipos)
        ->with('preparacoes_aplicacoes', $preparacoes_aplicacoes)
        ->with('preparacao',             $preparacao)
        ->with('preparacao_id',          $preparacao_id)
        ->with('cicloSituacao',          $cicloSituacao);
    }

    public function createPreparacoesAplicacoes(int $preparacao_id)
    {
        $preparacao = Preparacoes::find($preparacao_id);

        $preparacoes_tipos = PreparacoesTipos::orderBy('nome', 'asc')
        ->get();

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->where('em_estoque', '>', 0)
        ->whereIn('produto_tipo_id', [1, 4]) // INSUMOS, OUTROS
        ->get();
        
        return view('admin.preparacoes.aplicacoes.create')
        ->with('preparacoes_tipos', $preparacoes_tipos)
        ->with('produtos',          $produtos)
        ->with('preparacao',        $preparacao);
    }

    public function storePreparacoesAplicacoes(PreparacoesAplicacoesCreateFormRequest $request, int $preparacao_id)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data);

        $data['usuario_id'] = auth()->user()->id;
        
        $preparacao = Preparacoes::find($preparacao_id);
        
        $ciclo = $preparacao->ciclo;
        
        $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao'])->format('Y-m-d');
        $data_aplicacao .= ' 23:59:59';

        $message = 'A data de aplicação deve suceder as datas de início da preparação e do ciclo.';

        if ($preparacao->sucedeDataInicio($data_aplicacao) && $ciclo->sucedeDataInicio($data_aplicacao)) {
            
            if ($preparacao instanceof Preparacoes) {

                $data['preparacao_id'] = $preparacao->id;
    
                $preparacao_aplicacao = PreparacoesAplicacoes::create($data);
    
                if ($preparacao_aplicacao instanceof PreparacoesAplicacoes) {
    
                    $saida = EstoqueSaidas::registrarSaida([
                        'tanque_id'      => $preparacao->ciclo->tanque_id,
                        'data_movimento' => $preparacao_aplicacao->data_aplicacao,
                        'quantidade'     => $data['quantidade'],
                        'produto_id'     => $data['produto_id'],
                        'filial_id'      => session('_filial')->id,
                        'usuario_id'     => auth()->user()->id,
                        'tipo_destino'   => 1, // Saídas para preparação
                    ]);
    
                    if ($saida instanceof EstoqueSaidas) {
    
                        $saida_preparacao = EstoqueSaidasPreparacoes::create([
                            'estoque_saida_id'        => $saida->id,
                            'preparacao_aplicacao_id' => $preparacao_aplicacao->id,
                            'preparacao_id'           => $preparacao->id,
                            'tanque_id'               => $preparacao->ciclo->tanque_id,
                            'ciclo_id'                => $preparacao->ciclo_id,
                        ]);
    
                        if ($saida_preparacao instanceof EstoqueSaidasPreparacoes) {
                            return redirect()->route('admin.preparacoes.aplicacoes', ['preparacao_id' => $preparacao_id])
                            ->with('success', 'Registro salvo com sucesso!');
                        }
    
                        EstoqueSaidas::find($saida->id)->delete();
            
                    }
    
                }
    
            }

            $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        }
        
        return redirect()->back()->withInput()
        ->with('error', $message);
    }

    public function reversePreparacoesAplicacoes(Request $request, int $preparacao_id, int $id)
    {
        $rules = [
            'descricao' => 'required',
        ];

        $messages = [
            'descricao.required' => 'Informe uma justificativa para realizar o estorno.',
        ];

        $data = $this->validate($request, $rules, $messages);

        $data = DataPolisher::toPolish($data);

        $preparacao_aplicacao = PreparacoesAplicacoes::find($id);

        $redirect = redirect()->route('admin.preparacoes.aplicacoes', ['preparacao_id' => $preparacao_id]);

        if ($preparacao_aplicacao instanceof PreparacoesAplicacoes) {

            $justificativa = EstoqueEstornosJustificativas::create([
                'descricao'   => $data['descricao'],
                'tipo_origem' => 1, // Estorno de preparação
                'filial_id'   => session('_filial')->id,
                'usuario_id'  => auth()->user()->id,
            ]);

            if ($justificativa instanceof EstoqueEstornosJustificativas) {

                $saida = $preparacao_aplicacao->estoque_saida_preparacao->estoque_saida;

                if ($saida->tipo_destino == 1) { // Saídas para preparações

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

                            $preparacao_aplicacao->situacao = 'E'; // Aplicações estornadas

                            if ($preparacao_aplicacao->save()) {
                                return $redirect->with('success', 'Aplicação excluida com sucesso!');
                            }

                            return $redirect->with('error', 'Ocorreu um erro durante a tentativa de excluir a aplicação.');
                            
                        }

                        return $redirect->with('error', 'Ocorreu um erro durante a tentativa de alterar a situação da saída.');
                        
                    }

                }

            }

        }
        
        return $redirect->with('error', 'Ocorreu um erro durante a tentativa de registrar o estorno! Tente novamente.');
    }
}
