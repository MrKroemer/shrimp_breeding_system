<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\SondasLaboratoriaisCreateFormRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\SondasLaboratoriais;

class SondasLaboratoriaisController extends Controller
{
    private $rowsPerPage = 10;

    public function listingSondasLaboratoriais()
    {
        $sondas_laboratoriais = SondasLaboratoriais::listing($this->rowsPerPage);

        return view('admin.sondas_laboratoriais.list')
        ->with('sondas_laboratoriais', $sondas_laboratoriais);
    }
    
    public function searchSondasLaboratoriais(Request $request)
    {
        $formData = $request->except(['_token']);

        $sondas_laboratoriais = SondasLaboratoriais::search($formData, $this->rowsPerPage);

        return view('admin.sondas_laboratoriais.list')
        ->with('formData',   $formData)
        ->with('sondas_laboratoriais', $sondas_laboratoriais);
    }

    public function createSondasLaboratoriais()
    {
        return view('admin.sondas_laboratoriais.create');
    }

    public function storeSondasLaboratoriais(SondasLaboratoriaisCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        $insert = SondasLaboratoriais::create($data);

        if ($insert) {
            return redirect()->route('admin.sondas_laboratoriais')
            ->with('success', 'Sonda cadastrada com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a Sonda. Tente novamente!');
    }

    public function editSondasLaboratoriais(int $id)
    {
        $data = SondasLaboratoriais::find($id);

        return view('admin.sondas_laboratoriais.edit')
        ->with('id',             $id)
        ->with('nome',           $data['nome'])
        ->with('marca',          $data['marca'])
        ->with('numero_serie',   $data['numero_serie']);
    }

    public function updateSondasLaboratoriais(SondasLaboratoriaisCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['usuario_id'] = auth()->user()->id;

        $update = SondasLaboratoriais::find($id);

        if ($update->update($data)) {
            return redirect()->back()
            ->with('success', 'Sonda atualizada com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a Sonda. Tente novamente!');
    }

    public function removeSondasLaboratoriais(int $id)
    {
        $delete = SondasLaboratoriais::find($id);

        if ($delete->delete()) {
            return redirect()->back()
            ->with('success', 'Sonda excluida com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro ao excluir a Sonda. Tente novamente!');
    }

    public function turnSondasLaboratoriais(int $id)
    {
        $data = SondasLaboratoriais::find($id);

        $data->usuario_id = auth()->user()->id;

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->back()
            ->with('success', 'Sonda desabilitada com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.sondas_laboratoriais')
        ->with('success', 'Sonda habilitada com sucesso!');
    }
}
