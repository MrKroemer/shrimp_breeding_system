<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Modulos;
use App\Http\Requests\ModulosCreateFormRequest;
use App\Http\Resources\ModulosJsonResource;

class ModulosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingModulos()
    {
        //Listar os modulos por página.
        $modulos = Modulos::listing($this->rowsPerPage);

        return view('admin.modulos.list')
        ->with('modulos', $modulos);
    }

    public function searchModulos(Request $request)
    {
        $formData = $request->except(['_token']);

        $modulos = Modulos::search($formData, $this->rowsPerPage);

        return view('admin.modulos.list')
        ->with('formData', $formData)
        ->with('modulos',  $modulos);
    }

    public function createModulos()
    {
        $modulos = Modulos::listing($this->rowaPerPage);

        return view('admin.modulos.create');
           
    }

    public function storeModulos(ModulosCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data['nome']  = mb_strtoupper(trim($data['nome']));

        $insert = Modulos::create($data);

        if ($insert) {
            return redirect()->route('admin.modulos')
            ->with('success', 'Modulo cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('nome',  $data['nome'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o modulo. Tente novamente!');
    }

    public function editModulos(int $id)
    {
        $data = Modulos::find($id);

        return view('admin.modulos.edit')
        ->with('id',   $id)
        ->with('nome', $data['nome']);
    }

    public function updateModulos(ModulosCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data['nome']  = mb_strtoupper(trim($data['nome']));

        $update = Modulos::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.modulos')
            ->with('success', 'Modulo atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id',    $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o modulo. Tente novamente!');
    }

    public function removeModulos(int $id)
    {
        $data = Modulos::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.modulos')
            ->with('success', 'Modulo excluido com sucesso!');
        }
        
        return redirect()->route('admin.modulos')
        ->with('error', 'Ocorreu um erro ao excluir o modulo. Tente novamente!');
    }

    public function getJsonModulos(int $id)
    {
        if ($id == 0) {
            $modulos = Modulos::orderBy('id', 'asc')->get(['id', 'nome']);
        } else {
            $modulos = Modulos::where('id', '=', $id)->orderBy('id', 'asc')->get(['id', 'nome']);
        }
        
        return ModulosJsonResource::collection($modulos);
    }
}
