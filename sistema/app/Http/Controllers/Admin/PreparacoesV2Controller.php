<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Preparacoes;
use App\Models\Ciclos;
use App\Http\Requests\PreparacoesCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;

class PreparacoesV2Controller extends Controller
{
    private $rowsPerPage = 10;
    private $situacoes = [
        2 => 'Limpeza',
        3 => 'Abastecimento',
        4 => 'Fertilização',
    ];

    public function listingPreparacoes()
    {
        $preparacoes = Preparacoes::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', '<>', 1) // Vazio
        ->get();

        return view('admin.preparacoes_v2.list')
        ->with('preparacoes', $preparacoes)
        ->with('ciclos',      $ciclos)
        ->with('situacoes',   $this->situacoes);
    }

    public function searchPreparacoes(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $preparacoes = Preparacoes::search($formData, $this->rowsPerPage);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', '<>', 1) // Vazio
        ->get();

        return view('admin.preparacoes_v2.list')
        ->with('formData',    $formData)
        ->with('preparacoes', $preparacoes)
        ->with('ciclos',      $ciclos)
        ->with('situacoes',   $this->situacoes);
    }

    public function createPreparacoes()
    {
        $preparacoes = Preparacoes::where('filial_id', session('_filial')->id);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->whereNotIn('id', $preparacoes->groupBy('ciclo_id')->get(['ciclo_id']))
        ->get();

        return view('admin.preparacoes_v2.create')
        ->with('ciclos', $ciclos);
    }

    public function storePreparacoes(PreparacoesCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);
        
        $ciclo = Ciclos::find($data['ciclo_id']);
        
        $data_inicio = Carbon::createFromFormat('d/m/Y H:i', $data['data_inicio'])->format('Y-m-d H:i');

        $message = 'A data de início da preparação deve suceder a data de início do ciclo.';

        if ($ciclo->sucedeDataInicio($data_inicio)) {
            
            $data['filial_id']  = session('_filial')->id;
            $data['usuario_id'] = auth()->user()->id;
            $data['situacao']   = 'N';

            $preparacao = Preparacoes::create($data);

            if ($preparacao instanceof Preparacoes) {

                $ciclo = $preparacao->ciclo;
                $ciclo->situacao = 2; // Limpeza
                
                if ($ciclo->save()) {
                    return redirect()->route('admin.preparacoes_v2')
                    ->with('success', 'Registro salvo com sucesso!');
                }

            }

            $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        }

        return redirect()->back()
        ->withInput()
        ->with('error', $message);
    }

    public function editPreparacoes(int $id)
    {
        $data = Preparacoes::find($id);

        $tanque_sigla  = $data->ciclo->tanque->sigla;
        $cicloSituacao = $data->ciclo->verificarSituacao([2, 3, 4]);

        return view('admin.preparacoes_v2.edit')
        ->with('id',             $id)
        ->with('data_inicio' ,   $data->data_inicio())
        ->with('bandejas',       $data['bandejas'])
        ->with('ph_solo',        $data['ph_solo'])
        ->with('observacoes',    $data['observacoes'])
        ->with('ciclo_id',       $data['ciclo_id'])
        ->with('tanque_sigla',   $tanque_sigla)
        ->with('cicloSituacao',  $cicloSituacao);
    }

    public function updatePreparacoes(PreparacoesCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['usuario_id'] = auth()->user()->id;

        $update = Preparacoes::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.preparacoes_v2')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removePreparacoes(int $id)
    {
        $preparacao = Preparacoes::find($id);

        if ($preparacao->delete()) {

            $ciclo = $preparacao->ciclo;
            $ciclo->situacao = 1; // Vazio
            $ciclo->save();

            return redirect()->route('admin.preparacoes_v2')
            ->with('success', 'Registro excluido com sucesso!');

        }

        return redirect()->route('admin.preparacoes_v2')
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function turnPreparacoes(Request $request, int $id)
    {
        $data = $request->except('_token');
        
        $preparacao = Preparacoes::find($id);

        $ciclo = $preparacao->ciclo;
        $ciclo->situacao = $data['situacao'];

        if ($ciclo->save()) {
            return redirect()->back();
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de alterar o registro! Tente novamente.');
    }

    public function viewPreparacoes(int $id)
    {
        return $this->editPreparacoes($id);
    }

    public function closePreparacoes(int $id)
    {
        $preparacao = Preparacoes::find($id);

        $ciclo = $preparacao->ciclo;

        $data_fim = date('Y-m-d H:i');

        $message = 'A data de encerramento da preparação deve suceder as datas de início dela mesma e do ciclo.';

        if ($preparacao->sucedeDataInicio($data_fim) && $ciclo->sucedeDataInicio($data_fim)) {

            $preparacao->data_fim = $data_fim;
            $preparacao->situacao = 'V';

            if ($preparacao->save()) {

                $ciclo = $preparacao->ciclo;
                $ciclo->situacao = 5; // Povoamento

                if ($ciclo->save()) {
                    return redirect()->route('admin.preparacoes_v2')
                    ->with('success', 'Preparação encerrada com sucesso!');
                }

            }

            $message = 'Ocorreu um erro durante a tentativa de encerrar a preparação! Tente novamente.';

        }

        return redirect()->back()
        ->with('error', $message);
    }
}
