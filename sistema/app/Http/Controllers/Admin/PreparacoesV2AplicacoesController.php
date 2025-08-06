<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PreparacoesAplicacoes;
use App\Models\PreparacoesTipos;
use App\Models\Preparacoes;
use App\Models\Produtos;
use App\Models\VwEstoqueDisponivel;
use App\Models\EstoqueSaidas;
use App\Models\EstoqueSaidasPreparacoes;
use App\Http\Requests\PreparacoesAplicacoesCreateFormRequest;
use App\Http\Requests\PreparacoesAplicacoesEditFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;

class PreparacoesV2AplicacoesController extends Controller
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

        return view('admin.preparacoes_v2.aplicacoes.list')
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

        return view('admin.preparacoes_v2.aplicacoes.list')
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
        
        return view('admin.preparacoes_v2.aplicacoes.create')
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

        $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';
        
        if ($preparacao instanceof Preparacoes) {

            $ciclo = $preparacao->ciclo;
            
            $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao'])->format('Y-m-d');
            $data_aplicacao .= ' 23:59:59';

            $message = 'A data de aplicação deve suceder as datas de início da preparação e do ciclo.';

            if ($preparacao->sucedeDataInicio($data_aplicacao) && $ciclo->sucedeDataInicio($data_aplicacao)) {
                    
                $data['preparacao_id'] = $preparacao->id;
            
                $preparacao_aplicacao = PreparacoesAplicacoes::create($data);

                if ($preparacao_aplicacao instanceof PreparacoesAplicacoes) {
                    return redirect()->route('admin.preparacoes_v2.aplicacoes', ['preparacao_id' => $preparacao_id])
                    ->with('success', 'Registro salvo com sucesso!');
                }

            }

        }

        return redirect()->back()
        ->withInput()
        ->with('error', $message);
    }

    public function removePreparacoesAplicacoes(int $preparacao_id, int $id)
    {
        $preparacao_aplicacao = PreparacoesAplicacoes::find($id);

        if ($preparacao_aplicacao instanceof PreparacoesAplicacoes) {

            if ($preparacao_aplicacao->delete()) {
                return redirect()->route('admin.preparacoes_v2.aplicacoes', ['preparacao_id' => $preparacao_id])
                ->with('success', 'Registro excluido com sucesso!');
            }

        }

        return redirect()->route('admin.preparacoes_v2.aplicacoes', ['preparacao_id' => $preparacao_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function updatePreparacoesAplicacoes(PreparacoesAplicacoesEditFormRequest $request, int $preparacao_id, int $id)
    {
        $data = $request->except(['_token']);

        $preparacao_aplicacao = PreparacoesAplicacoes::find($id);

        if ($preparacao_aplicacao instanceof PreparacoesAplicacoes) {

            if ($preparacao_aplicacao->update($data)) {
                return redirect()->route('admin.preparacoes_v2.validacoes', ['preparacao_id' => $preparacao_id])
                ->with('success', 'Registro salvo com sucesso!');
            }

        }

        return redirect()->route('admin.preparacoes_v2.validacoes', ['preparacao_id' => $preparacao_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function closeTest()
    {
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
                    return redirect()->route('admin.preparacoes_v2.aplicacoes', ['preparacao_id' => $preparacao_id])
                    ->with('success', 'Registro salvo com sucesso!');
                }

                EstoqueSaidas::find($saida->id)->delete();
    
            }

        }
    }
}
