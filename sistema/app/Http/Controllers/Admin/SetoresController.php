<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SetoresCreateFormRequest;
use App\Models\Setores;
use App\Models\Filiais;
use App\Http\Controllers\Util\DataPolisher;

class SetoresController extends Controller
{
    private $rowsPerPage = 10;

    public function listing()
    {
        $setores = Setores::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        return view('admin.setores.list')
        ->with('setores', $setores);
    }

    public function search(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $setores = Setores::search($formData, $this->rowsPerPage);

        return view('admin.setores.list')
        ->with('formData', $formData)
        ->with('setores',  $setores);
    }

    public function create()
    {
        return view('admin.setores.create')
        ->with('tipos', (new Setores)->tipo());
    }

    public function store(SetoresCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data);

        $data['filial_id'] = session('_filial')->id;

        $create = Setores::create($data);

        if ($create) {
            return redirect()->route('admin.setores')
            ->with('success', 'Setor cadastrado com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o setor. Tente novamente!');
    }

    public function edit(int $id)
    {        
        $data = Setores::find($id);

        return view('admin.setores.edit')
        ->with('tipos', (new Setores)->tipo())
        ->with('id',    $data['id'])
        ->with('nome',  $data['nome'])
        ->with('sigla', $data['sigla'])
        ->with('tipo',  $data['tipo']);
    }

    public function update(SetoresCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $update = Setores::find($id)->update($data);

        if ($update) {
            return redirect()->route('admin.setores')
            ->with('success', 'Setor atualizada com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o setor. Tente novamente!');
    }

    public function remove(int $id)
    {
        $data = Setores::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.setores')
            ->with('success', 'Setor excluido com sucesso!');
        } 
        
        return redirect()->route('admin.setores')
        ->with('error', 'Ocorreu um erro ao excluir o setor. Tente novamente!');
    }
}
