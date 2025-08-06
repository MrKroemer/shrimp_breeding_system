<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Preparacoes;
use App\Models\Ciclos;
use App\Http\Requests\PreparacoesCreateFormRequest;
use App\Http\Requests\PreparacoesEditFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;

class PreparacoesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingPreparacoes()
    {
        $preparacoes = Preparacoes::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', '<>', 1) // Vazio
        ->orderBy('numero', 'desc')
        ->get();

        return view('admin.preparacoes.list')
        ->with('preparacoes', $preparacoes)
        ->with('ciclos',      $ciclos);
    }

    public function searchPreparacoes(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $preparacoes = Preparacoes::search($formData, $this->rowsPerPage);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', '<>', 1) // Vazio
        ->orderBy('numero', 'desc')
        ->get();

        return view('admin.preparacoes.list')
        ->with('formData',    $formData)
        ->with('preparacoes', $preparacoes)
        ->with('ciclos',      $ciclos);
    }

    public function createPreparacoes()
    {
        $preparacoes = Preparacoes::where('filial_id', session('_filial')->id);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->whereNotIn('id', $preparacoes->groupBy('ciclo_id')->get(['ciclo_id']))
        ->orderBy('numero', 'desc')
        ->get();

        return view('admin.preparacoes.create')
        ->with('ciclos', $ciclos);
    }

    public function storePreparacoes(PreparacoesCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_NULL']);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;
        
        $ciclo = Ciclos::find($data['ciclo_id']);
        
        $data_inicio = Carbon::createFromFormat('d/m/Y', $data['data_inicio'])->format('Y-m-d');

        $message = 'A data de início da preparação deve suceder a data de início do ciclo.';

        if ($ciclo->sucedeDataInicio($data_inicio)) {

            $data = $this->checkDatesSequence($data, $ciclo);

            if (! is_array($data)) {
                return $data;
            }

            $preparacao = Preparacoes::create($data);

            if ($preparacao instanceof Preparacoes) {
                return redirect()->route('admin.preparacoes')
                ->with('success', 'Registro salvo com sucesso!');
            }

            $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        }

        return redirect()->back()->withInput()
        ->with('error', $message);
    }

    public function editPreparacoes(int $id)
    {
        $data = Preparacoes::find($id);

        $cicloSituacao = $data->ciclo->verificarSituacao([2, 3, 4]);

        return view('admin.preparacoes.edit')
        ->with('id',            $id)
        ->with('data_inicio' ,  $data->data_inicio())
        ->with('data_fim' ,     $data->data_fim())
        ->with('abas_inicio' ,  $data->abas_inicio())
        ->with('abas_fim' ,     $data->abas_fim())
        ->with('bandejas',      $data->bandejas)
        ->with('ph_solo',       $data->ph_solo)
        ->with('observacoes',   $data->observacoes)
        ->with('ciclo',         $data->ciclo)
        ->with('cicloSituacao', $cicloSituacao);
    }

    public function updatePreparacoes(PreparacoesEditFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_NULL']);

        $data['usuario_id'] = auth()->user()->id;

        $preparacao = Preparacoes::find($id);

        $ciclo = $preparacao->ciclo;

        $data_inicio = Carbon::createFromFormat('d/m/Y', $data['data_inicio'])->format('Y-m-d');

        $message = 'A data de início da preparação deve suceder a data de início do ciclo.';

        if ($ciclo->sucedeDataInicio($data_inicio)) {

            $data = $this->checkDatesSequence($data, $ciclo);

            if (! is_array($data)) {
                return $data;
            }

            if ($preparacao->update($data)) {
                return redirect()->route('admin.preparacoes')
                ->with('success', 'Registro salvo com sucesso!');
            }

            $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';
        
        }

        return redirect()->back()->withInput()
        ->with('id', $id)
        ->with('error', $message);
    }

    public function removePreparacoes(int $id)
    {
        $preparacao = Preparacoes::find($id);

        if ($preparacao->delete()) {

            $ciclo = $preparacao->ciclo;
            $ciclo->situacao = 1; // Vazio
            $ciclo->save();

            return redirect()->route('admin.preparacoes')
            ->with('success', 'Registro excluido com sucesso!');

        }

        return redirect()->route('admin.preparacoes')
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function viewPreparacoes(int $id)
    {
        return $this->editPreparacoes($id);
    }

    private function checkDatesSequence(Array $data, Ciclos $ciclo)
    {
        $dates[] = Carbon::createFromFormat('d/m/Y', $data['data_inicio']);
        $ciclo->situacao = 2; // LIMPEZA

        if (! is_null($data['abas_inicio'])) {
            $dates[] = Carbon::createFromFormat('d/m/Y', $data['abas_inicio']);
            $ciclo->situacao = 3; // ABASTECIMENTO
        }

        if (! is_null($data['abas_fim'])) {
            if (! is_null($data['abas_inicio'])) {
                $dates[] = Carbon::createFromFormat('d/m/Y', $data['abas_fim']);
                $ciclo->situacao = 4; // FERTILIZAÇÃO
            } else {
                $data['abas_fim'] = null;
            }
        }

        if (! is_null($data['data_fim'])) {
            if (! is_null($data['abas_fim'])) {
                if (! is_null($data['abas_inicio'])) {
                    $dates[] = Carbon::createFromFormat('d/m/Y', $data['data_fim']);
                    $ciclo->situacao = 5; // POVOAMENTO
                } else {
                    $data['abas_fim'] = null;
                    $data['data_fim'] = null;
                }
            } else {
                $data['data_fim'] = null;
            }
        }

        foreach ($dates as $date1) {

            $date2 = next($dates);

            if (! empty($date2)) {

                if ($date1->greaterThan($date2)) {
                    return redirect()->back()->withInput()
                    ->with('warning', 'A data posterior deve suceder a anterior.');
                }

            }

        }

        if (! $ciclo->save()) {
            return redirect()->back()->withInput()
            ->with('error', 'Não foi possível alterar o status do ciclo.');
        }

        return $data;
    }
}
