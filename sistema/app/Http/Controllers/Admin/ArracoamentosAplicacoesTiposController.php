<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArracoamentosAplicacoesTipos;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Requests\ArracoamentosAplicacoesTiposCreateFormRequest;
use App\Http\Requests\ArracoamentosAplicacoesTiposEditFormRequest;

class ArracoamentosAplicacoesTiposController extends Controller
{
    private $rowsPerPage = 10;

    public function listingArracoamentosAplicacoesTipos()
    {
        $arracoamentos_aplicacoes_tipos = ArracoamentosAplicacoesTipos::listing($this->rowsPerPage);

        return view('admin.arracoamentos_aplicacoes.tipos.list')
        ->with('arracoamentos_aplicacoes_tipos', $arracoamentos_aplicacoes_tipos);
    }

    public function searchArracoamentosAplicacoesTipos(Request $request)
    {
        $formData = $request->except(['_token']);

        $arracoamentos_aplicacoes_tipos = ArracoamentosAplicacoesTipos::search($formData, $this->rowsPerPage);

        return view('admin.arracoamentos_aplicacoes.tipos.list')
        ->with('formData', $formData)
        ->with('arracoamentos_aplicacoes_tipos', $arracoamentos_aplicacoes_tipos);
    }

    public function createArracoamentosAplicacoesTipos()
    {
        return view('admin.arracoamentos_aplicacoes.tipos.create');
    }

    public function storeArracoamentosAplicacoesTipos(ArracoamentosAplicacoesTiposCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data);

        $data['nome'] = mb_strtoupper($data['nome']);

        $insert = ArracoamentosAplicacoesTipos::create($data);

        if ($insert) {
            return redirect()->route('admin.arracoamentos_aplicacoes_tipos')
            ->with('success', 'Tipo de aplicação cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('nome',  $data['nome'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o tipo de aplicação. Tente novamente!');
    }

    public function editArracoamentosAplicacoesTipos(int $id)
    {                
        $data = ArracoamentosAplicacoesTipos::find($id);

        return view('admin.arracoamentos_aplicacoes.tipos.edit')
        ->with('id',   $id)
        ->with('nome', $data['nome']);
    }

    public function updateArracoamentosAplicacoesTipos(ArracoamentosAplicacoesTiposEditFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $update = ArracoamentosAplicacoesTipos::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.arracoamentos_aplicacoes_tipos')
            ->with('success', 'Tipo de aplicação atualizado com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o tipo de aplicação. Tente novamente!');
    }

    public function removeArracoamentosAplicacoesTipos(int $id)
    {
        $data = ArracoamentosAplicacoesTipos::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.arracoamentos_aplicacoes_tipos')
            ->with('success', 'Tipo de aplicação excluido com sucesso!');
        } 
        
        return redirect()->route('admin.arracoamentos_aplicacoes_tipos')
        ->with('error', 'Ocorreu um erro ao excluir o tipo de aplicação. Tente novamente!');
    }
}
