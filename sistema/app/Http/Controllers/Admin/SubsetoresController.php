<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubsetoresCreateFormRequest;
use App\Models\Subsetores;
use App\Http\Resources\SubsetoresJsonResource;

class SubsetoresController extends Controller
{
    private $rowsPerPage = 10;

    public function listingSubsetores(int $setor_id)
    {
        $subsetores = Subsetores::listing($this->rowsPerPage, [
            'setor_id' => $setor_id,
        ]);

        return view('admin.subsetores.list')
        ->with('subsetores', $subsetores)
        ->with('setor_id',   $setor_id);
    }
    
    public function searchSubsetores(Request $request, int $setor_id)
    {
        $formData = $request->except(['_token']);
        $formData['setor_id'] = $setor_id;

        $subsetores = Subsetores::search($formData, $this->rowsPerPage);

        return view('admin.subsetores.list')
        ->with('formData',   $formData)
        ->with('subsetores', $subsetores)
        ->with('setor_id',   $setor_id);
    }

    public function createSubsetores(int $setor_id)
    {
        return view('admin.subsetores.create')
        ->with('setor_id', $setor_id);
    }

    public function storeSubsetores(SubsetoresCreateFormRequest $request, int $setor_id)
    {
        $data = $request->except(['_token']);
        
        $data['nome']     = trim($data['nome']);
        $data['sigla']    = trim($data['sigla']);
        $data['setor_id'] = $setor_id;

        if (empty($data['nome'])) {
            unset($data['nome']);
        }

        if (empty($data['sigla'])) {
            unset($data['sigla']);
        }

        $insert = Subsetores::create($data);

        if ($insert) {
            return redirect()->route('admin.setores.subsetores', ['setor_id' => $setor_id])
            ->with('success', 'Subsetor cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('nome',      $data['nome'])
        ->with('sigla',     $data['sigla'])
        ->with('setor_id',  $setor_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o subsetor. Tente novamente!');
    }

    public function editSubsetores(int $setor_id, int $id)
    {
        $data = Subsetores::find($id);

        return view('admin.subsetores.edit')
        ->with('id',       $id)
        ->with('nome',     $data['nome'])
        ->with('sigla',    $data['sigla'])
        ->with('setor_id', $setor_id);
    }

    public function updateSubsetores(SubsetoresCreateFormRequest $request, int $setor_id, int $id)
    {
        $data = $request->except(['_token']);

        $data['nome']      = trim($data['nome']);
        $data['sigla']     = trim($data['sigla']);
        
        if (empty($data['nome'])) {
            unset($data['nome']);
        }

        if (empty($data['sigla'])) {
            unset($data['sigla']);
        }

        $update = Subsetores::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.setores.subsetores', ['setor_id' => $setor_id])
            ->with('success', 'Subsetor atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id',        $id)
        ->with('setor_id',  $setor_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o subsetor. Tente novamente!');
    }

    public function removeSubsetores(int $setor_id, int $id)
    {
        $data = Subsetores::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.setores.subsetores', ['setor_id' => $setor_id])
            ->with('success', 'Subsetor excluido com sucesso!');
        } 
        
        return redirect()->route('admin.setores.subsetores', ['setor_id' => $setor_id])
        ->with('error', 'Ocorreu um erro ao excluir o subsetor. Tente novamente!');
    }

    
    public function getJsonSubsetores(int $setor_id)
    {
        if (is_numeric($setor_id) && $setor_id > 0) {
            return SubsetoresJsonResource::collection(Subsetores::where('setor_id', $setor_id)->get());
        }

        return SubsetoresJsonResource::collection(Subsetores::all());
    }
}
