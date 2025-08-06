<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GruposCreateFormRequest;
use App\Models\Grupos;

class GruposController extends Controller
{
    private $rowsPerPage = 10;

    public function listingGrupos()
    {
        $grupos = Grupos::listing($this->rowsPerPage);

        return view('admin.grupos.list')
        ->with('grupos', $grupos);
    }
    
    public function searchGrupos(Request $request)
    {
        $formData = $request->except(['_token']);

        $grupos = Grupos::search($formData, $this->rowsPerPage);

        return view('admin.grupos.list')
        ->with('formData', $formData)
        ->with('grupos', $grupos);
    }

    public function createGrupos()
    {
        return view('admin.grupos.create');
    }

    public function storeGrupos(GruposCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data['nome']     = trim($data['nome']);

        if (empty($data['nome'])) {
            unset($data['nome']);
        }

        $insert = Grupos::create($data);

        if ($insert) {
            return redirect()->route('admin.grupos')
            ->with('success', 'Grupo cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('nome',     $data['nome'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o grupo. Tente novamente!');
    }

    public function editGrupos(int $id)
    {
        $data = Grupos::find($id);

        return view('admin.grupos.edit')
        ->with('id',       $data['id'])
        ->with('nome',     $data['nome']);
    }

    public function updateGrupos(GruposCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token', 'password_confirmation']);

        $data['nome']     = trim($data['nome']);
        
        if (empty($data['nome'])) {
            unset($data['nome']);
        }

        $update = Grupos::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.grupos')
            ->with('success', 'Grupo atualizado com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o grupo. Tente novamente!');
    }

    public function removeGrupos(int $id)
    {
        $data = Grupos::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.grupos')
            ->with('success', 'Grupo excluido com sucesso!');
        } 

        return redirect()->route('admin.grupos')
        ->with('error', 'Ocorreu um erro ao excluir o grupo. Tente novamente!');
    }

    public function turnGrupos(int $id)
    {
        $data = Grupos::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.grupos')
            ->with('success', 'Grupo desabilitado com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.grupos')
        ->with('success', 'Grupo habilitado com sucesso!');
    }
}
