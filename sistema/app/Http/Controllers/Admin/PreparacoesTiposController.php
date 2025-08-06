<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PreparacoesTipos;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Requests\PreparacoesTiposCreateFormRequest;

class PreparacoesTiposController extends Controller
{
    private $rowsPerPage = 10;

    public function listingPreparacoesTipos()
    {
        $preparacoes_tipos = PreparacoesTipos::listing($this->rowsPerPage);

        return view('admin.preparacoes.tipos.list')
        ->with('preparacoes_tipos', $preparacoes_tipos);
    }

    public function searchPreparacoesTipos(Request $request)
    {
        $formData = $request->except(['_token']);

        $preparacoes_tipos = PreparacoesTipos::search($formData, $this->rowsPerPage);

        return view('admin.preparacoes.tipos.list')
        ->with('formData',          $formData)
        ->with('preparacoes_tipos', $preparacoes_tipos);
    }

    public function createPreparacoesTipos(PreparacoesTipos $preparacoes_tipos)
    {
        $metodos = $preparacoes_tipos->metodo();

        return view('admin.preparacoes.tipos.create')
        ->with('metodos', $metodos);
    }

    public function storePreparacoesTipos(PreparacoesTiposCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data);

        $insert = PreparacoesTipos::create($data);

        if ($insert) {
            return redirect()->route('admin.preparacoes_tipos')
            ->with('success', 'Tipo de preparação cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('nome',      $data['nome'])
        ->with('descricao', $data['descricao'])
        ->with('metodo',    $data['metodo'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o tipo de preparação. Tente novamente!');
    }

    public function editPreparacoesTipos(PreparacoesTipos $preparacoes_tipos, int $id)
    {                
        $data    = $preparacoes_tipos->find($id);
        $metodos = $preparacoes_tipos->metodo();

        return view('admin.preparacoes.tipos.edit')
        ->with('id',        $id)
        ->with('nome',      $data['nome'])
        ->with('descricao', $data['descricao'])
        ->with('metodo',    $data['metodo'])
        ->with('metodos',   $metodos);
    }

    public function updatePreparacoesTipos(PreparacoesTiposCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $update = PreparacoesTipos::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.preparacoes_tipos')
            ->with('success', 'Tipo de preparação atualizado com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o tipo de preparação. Tente novamente!');
    }

    public function removePreparacoesTipos(int $id)
    {
        $data = PreparacoesTipos::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.preparacoes_tipos')
            ->with('success', 'Tipo de preparação excluido com sucesso!');
        } 
        
        return redirect()->route('admin.preparacoes_tipos')
        ->with('error', 'Ocorreu um erro ao excluir o tipo de preparação. Tente novamente!');
    }

    public function turnPreparacoesTipos(int $id)
    {
        $data = PreparacoesTipos::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.preparacoes_tipos')
            ->with('success', 'Tipo de preparação desabilitada com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.preparacoes_tipos')
        ->with('success', 'Tipo de preparação habilitada com sucesso!');
    }
}
