<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Despescas;
use App\Models\VwDespescas;
use App\Models\Ciclos;
use App\Models\Tanques;
use App\Models\Povoamentos;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Requests\DespescasCreateFormRequest;
use Carbon\Carbon;

class DespescasController extends Controller
{
    private $rowsPerPage = 10;
    
    public function listingDespescas()
    {
        $despescas = VwDespescas::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', 6) // Engorda
        ->orderBy('numero', 'desc')
        ->get();

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->whereIn('tanque_tipo_id', [1, 6]) // Viveiro de camarões, viveiro de peixes
        ->orderBy('sigla', 'asc')
        ->get();

        return view('admin.despescas.list')
        ->with('ciclos',    $ciclos)
        ->with('tanques',   $tanques)
        ->with('despescas', $despescas);
    }
    
    public function searchDespescas(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $despescas = VwDespescas::search($formData, $this->rowsPerPage);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', 6) // Engorda
        ->orderBy('numero', 'desc')
        ->get();

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->whereIn('tanque_tipo_id', [1, 6]) // Viveiro de camarões, viveiro de peixes
        ->orderBy('sigla', 'asc')
        ->get();

        return view('admin.despescas.list')
        ->with('formData',  $formData)
        ->with('ciclos',    $ciclos)
        ->with('tanques',   $tanques)
        ->with('despescas', $despescas);
    }

    public function createDespescas()
    {
        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->whereNotIn('situacao', [1, 2, 8]) // Vazio, Limpeza e Encerrado
        ->orderBy('numero', 'desc')
        ->get();

        return view('admin.despescas.create')
        ->with('ciclos', $ciclos);
    }

    public function storeDespescas(DespescasCreateFormRequest $request)
    {
        $data = $request->except(['_token', 'radio_button01']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

        $ciclo = Ciclos::find($data['ciclo_id']);

        $data_inicio = Carbon::createFromFormat('d/m/Y H:i', $data['data_inicio'])->format('Y-m-d H:i');
        $data_fim = DataPolisher::dateTimeRoundMinutes(5);

        if (isset($data['data_fim']) && ! empty($data['data_fim'])) {
            $data_fim = Carbon::createFromFormat('d/m/Y H:i', $data['data_fim'])->format('Y-m-d H:i');
        }

        if (! $ciclo instanceof Ciclos) {
            return redirect()->back()->withInput()
            ->with('error', 'Ciclo não encontrado');
        }

        if (! ($ciclo->sucedeDataInicio($data_inicio) && strtotime($data_inicio) <= strtotime($data_fim))) {
            return redirect()->back()->withInput()
            ->with('error', 'A data de início da despesca deve suceder o início do ciclo e anteceder a data de hoje.');
        }

        $despesca = Despescas::where('ciclo_id', $data['ciclo_id'])
        ->orderBy('ordinal', 'desc')
        ->first();

        $ordinal = 1;

        if (! is_null($despesca)) {
            $ordinal = $despesca->ordinal + 1;
        }

        $data['data_inicio'] = $data_inicio;
        $data['data_fim']    = $data_fim;
        $data['ordinal']     = $ordinal;
        $data['tipo']        = $data['tipo_despesca'];
        $data['filial_id']   = session('_filial')->id;
        $data['usuario_id']  = auth()->user()->id;

        $povoamento = $ciclo->povoamento;

        switch ($data['tipo_despesca']) {

            case 1: // Despesca completa
            case 2: // Despesca parcial
            case 4: // Descarte pós povoamento

                if (! $povoamento instanceof Povoamentos) {
                    return redirect()->back()->withInput()
                    ->with('error', 'O ciclo não pode ser despescado, pois não possui povoamento registrado.');
                }

                if (! $povoamento->sucedeDataFim($data_inicio)) {
                    return redirect()->back()->withInput()
                    ->with('error', 'A data de início da despesca deve suceder o início do povoamento.');
                }

                if (! $ciclo->arracoamentosTodosValidados()) {
                    return redirect()->back()->withInput()
                    ->with('error', 'O ciclo não pode ser despescado, pois possui arracoamentos não validados.');
                }

                break;

            case 3: // Descarte pré povoamento

                if ($povoamento instanceof Povoamentos) {
                    return redirect()->back()->withInput()
                    ->with('error', 'O ciclo não pode ser despescado como "Descaste pré povoamento", pois possui povoamento registrado.');
                }

                $data['qtd_prevista']   = 0;
                $data['qtd_despescada'] = 0;
                $data['peso_medio']     = 0;

                break;

        }

        $despesca = Despescas::create($data);

        if ($despesca instanceof Despescas) {

            $ciclo->situacao = 8; // Encerrado
            $ciclo->data_fim = $data_fim;
        
            if ($data['tipo_despesca'] == 2) {
                $ciclo->situacao = 6; // Engorda
                $ciclo->data_fim = null;
            }

            if ($ciclo->save()) {
                return redirect()->route('admin.despescas')
                ->with('success', 'Registro salvo com sucesso!');
            }

        }

        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function viewDespescas(int $id)
    {
        $data = Despescas::find($id);

        return view('admin.despescas.edit')
        ->with('ciclo',          $data->ciclo)
        ->with('data_inicio',    $data->data_inicio())
        ->with('data_fim',       $data->data_fim())
        ->with('qtd_prevista',   $data['qtd_prevista'])
        ->with('qtd_despescada', $data['qtd_despescada'])
        ->with('peso_medio',     $data['peso_medio'])
        ->with('tipo',           $data['tipo'])
        ->with('observacoes',    $data['observacoes']);
    }

    public function removeDespescas(int $id)
    {
        $despesca = Despescas::find($id);

        if ($despesca->delete()) {

            $despescas = Despescas::where('ciclo_id', $despesca->ciclo_id)
            ->orderBy('ordinal')
            ->get();

            if ($despescas->count() > 0) { // Caso existam despescas parciais

                $ordinal = 1;

                foreach ($despescas as $item) {

                    $item->ordinal    = $ordinal;
                    $item->usuario_id = auth()->user()->id;

                    $ordinal ++;

                    if (! $item->save()) {
                        return redirect()->back()
                        ->with('error', 'Não foi possível alterar a ordenação das demais despescas.');
                    }

                }

            } else {

                $ciclo = $despesca->ciclo;

                $ciclo->situacao   = 6; // Engorda
                $ciclo->data_fim   = null;
                $ciclo->usuario_id = auth()->user()->id;
                
                if (! $ciclo->save()) {
                    return redirect()->back()
                    ->with('error', 'Não foi possível retornar o ciclo para o período de engorda.');
                }

            }

            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');

        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
