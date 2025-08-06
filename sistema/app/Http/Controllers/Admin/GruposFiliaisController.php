<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GruposFiliaisCreateFormRequest;
use App\Models\Filiais;
use App\Models\Grupos;
use App\Models\GruposFiliais;

class GruposFiliaisController extends Controller
{
    private $rowsPerPage = 10;

    public function listingGruposFiliais(int $grupo_id)
    {
        $grupos_filiais = GruposFiliais::listing($this->rowsPerPage, [
            'grupo_id' => $grupo_id
        ]);

        $grupo = Grupos::find($grupo_id);

        $filiais = Filiais::orderBy('nome')->get();

        return view('admin.grupos.filiais.list')
        ->with('grupos_filiais', $grupos_filiais)
        ->with('grupo',          $grupo)
        ->with('filiais',        $filiais);
    }

    public function searchGruposFiliais(Request $request, int $grupo_id)
    {
        $formData = $request->except(['_token']);
        $formData['grupo_id'] = $grupo_id;

        $grupos_filiais = GruposFiliais::search($formData, $this->rowsPerPage);

        $grupo = Grupos::find($grupo_id);

        $filiais = Filiais::orderBy('nome')->get();

        return view('admin.grupos.filiais.list')
        ->with('formData',       $formData)
        ->with('grupos_filiais', $grupos_filiais)
        ->with('grupo',          $grupo)
        ->with('filiais',        $filiais);
    }

    public function storeGruposFiliais(GruposFiliaisCreateFormRequest $request, int $grupo_id)
    {
        $data = $request->except(['_token']);

        $data['grupo_id'] = $grupo_id;

        $filiais = GruposFiliais::where('grupo_id', $data['grupo_id'])
        ->where('filial_id', $data['filial_id']);

        if ($filiais->count() > 0) {
            return redirect()->route('admin.grupos.filiais', ['grupo_id' => $grupo_id])
            ->with('warning', 'O grupo já está associado a esta filial!');
        }

        $insert = GruposFiliais::create($data);

        if ($insert) {
            return redirect()->route('admin.grupos.filiais', ['grupo_id' => $grupo_id])
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->route('admin.grupos.filiais', ['grupo_id' => $grupo_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeGruposFiliais(int $grupo_id, int $id)
    {
        $data = GruposFiliais::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.grupos.filiais', ['grupo_id' => $grupo_id])
            ->with('success', 'Registro excluído com sucesso!');
        } 

        return redirect()->route('admin.grupos.filiais', ['grupo_id' => $grupo_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente!');
    }

    public function turnGruposFiliais(int $grupo_id, int $id)
    {
        $data = GruposFiliais::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.grupos.filiais', ['grupo_id' => $grupo_id])
            ->with('success', 'Filial desabilitada com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.grupos.filiais', ['grupo_id' => $grupo_id])
        ->with('success', 'Filial habilitado com sucesso!');
    }
}
