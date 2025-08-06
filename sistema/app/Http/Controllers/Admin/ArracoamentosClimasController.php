<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArracoamentosClimas;
use App\Http\Requests\ArracoamentosClimasCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
//use DB;

class ArracoamentosClimasController extends Controller
{
    private $rowsPerPage = 10;

    public function listingArracoamentosClimas()
    {
        $arracoamentos_climas = ArracoamentosClimas::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        return view('admin.arracoamentos_climas.list')
        ->with('arracoamentos_climas', $arracoamentos_climas);
    }

    public function searchArracoamentosClimas(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $arracoamentos_climas = ArracoamentosClimas::search($formData, $this->rowsPerPage);

        return view('admin.arracoamentos_climas.list')
        ->with('formData',             $formData)
        ->with('arracoamentos_climas', $arracoamentos_climas);
    }

    public function createArracoamentosClimas()
    {
        return view('admin.arracoamentos_climas.create');
    }

    public function storeArracoamentosClimas(ArracoamentosClimasCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id'] = session('_filial')->id;

        $insert = ArracoamentosClimas::create($data);

        if ($insert) {
            return redirect()->route('admin.arracoamentos_climas')
            ->with('success', 'Período climático cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('nome',      $data['nome'])
        ->with('descricao', $data['descricao'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o registro. Tente novamente!');
    }

    public function editArracoamentosClimas(int $id)
    {
        $data = ArracoamentosClimas::find($id);

        return view('admin.arracoamentos_climas.edit')
        ->with('id',        $data['id'])
        ->with('nome',      $data['nome'])
        ->with('descricao', $data['descricao']);
    }

    public function updateArracoamentosClimas(ArracoamentosClimasCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $update = ArracoamentosClimas::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.arracoamentos_climas')
            ->with('success', 'Período climático atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o registro. Tente novamente!');
    }

    public function removeArracoamentosClimas(int $id)
    {
        $data = ArracoamentosClimas::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.arracoamentos_climas')
            ->with('success', 'Período climático excluido com sucesso!');
        } 
        
        return redirect()->route('admin.arracoamentos_climas')
        ->with('error', 'Oops! Algo de errado ocorreu ao excluir o registro. Tente novamente!');
    }

    public function turnArracoamentosClimas(int $id)
    {
        $data = ArracoamentosClimas::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.arracoamentos_climas')
            ->with('success', 'Período climático desabilitado com sucesso!');

        }

        ArracoamentosClimas::table('arracoamentos_climas')->update(['situacao' => 'OFF']);

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.arracoamentos_climas')
        ->with('success', 'Período climático habilitado com sucesso!');
    }
}
