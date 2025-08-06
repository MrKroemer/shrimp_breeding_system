<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FiliaisCreateFormRequest;
use App\Http\Requests\FiliaisEditFormRequest;
use App\Models\Filiais;

class FiliaisController extends Controller
{
    private $rowsPerPage = 10;

    public function listingFiliais()
    {
        $filiais = Filiais::listing($this->rowsPerPage);

        return view('admin.filiais.list')
        ->with('filiais',  $filiais);
    }
    
    public function searchFiliais(Request $request)
    {
        $formData = $request->except(['_token']);

        $filiais = Filiais::search($formData, $this->rowsPerPage);

        return view('admin.filiais.list')
        ->with('formData', $formData)
        ->with('filiais',  $filiais);
    }

    public function createFiliais()
    {
        return view('admin.filiais.create');
    }

    public function storeFiliais(FiliaisCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data['nome']     = trim($data['nome']);
        $data['cidade']   = trim($data['cidade']);
        $data['endereco'] = trim($data['endereco']);
        $data['cnpj']     = trim($data['cnpj']);

        if (empty($data['nome'])) {
            unset($data['nome']);
        }

        if (empty($data['cidade'])) {
            unset($data['cidade']);
        }

        if (empty($data['endereco'])) {
            unset($data['endereco']);
        }

        if (empty($data['cnpj'])) {
            unset($data['cnpj']);
        }

        $insert = Filiais::create($data);

        if ($insert) {
            return redirect()->route('admin.filiais')
            ->with('success', 'Filial cadastrada com sucesso!');
        }

        return redirect()->back()
        ->with('nome',     $data['nome'])
        ->with('cidade',   $data['cidade'])
        ->with('endereco', $data['endereco'])
        ->with('cnpj',     $data['cnpj'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a filial. Tente novamente!');
    }

    public function editFiliais(int $id)
    {
        $data = Filiais::find($id);

        return view('admin.filiais.edit')
        ->with('id',       $data['id'])
        ->with('nome',     $data['nome'])
        ->with('cidade',   $data['cidade'])
        ->with('endereco', $data['endereco'])
        ->with('cnpj',     $data['cnpj']);
    }

    public function updateFiliais(FiliaisEditFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data['nome']     = trim($data['nome']);
        $data['cidade']   = trim($data['cidade']);
        $data['endereco'] = trim($data['endereco']);
        $data['cnpj']     = trim($data['cnpj']);

        if (empty($data['nome'])) {
            unset($data['nome']);
        }

        if (empty($data['cidade'])) {
            unset($data['cidade']);
        }

        if (empty($data['endereco'])) {
            unset($data['endereco']);
        }

        if (empty($data['cnpj'])) {
            unset($data['cnpj']);
        }

        $update = Filiais::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.filiais')
            ->with('success', 'Filial atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a filial. Tente novamente!');
    }

    public function removeFiliais(int $id)
    {
        $data = Filiais::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.filiais')
            ->with('success', 'Filial excluida com sucesso!');
        } 
        
        return redirect()->route('admin.filiais')
        ->with('error', 'Ocorreu um erro ao excluir a filial. Tente novamente!');
    }
}
