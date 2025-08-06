<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArracoamentosPerfis;
use App\Http\Requests\ArracoamentosPerfisCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class ArracoamentosPerfisController extends Controller
{
    private $rowsPerPage = 10;

    public function listingArracoamentosPerfis()
    {
        $arracoamentos_perfis = ArracoamentosPerfis::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        return view('admin.arracoamentos_perfis.list')
        ->with('arracoamentos_perfis', $arracoamentos_perfis);
    }

    public function searchArracoamentosPerfis(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $arracoamentos_perfis = ArracoamentosPerfis::search($formData, $this->rowsPerPage);

        return view('admin.arracoamentos_perfis.list')
        ->with('formData',             $formData)
        ->with('arracoamentos_perfis', $arracoamentos_perfis);
    }

    public function createArracoamentosPerfis()
    {
        return view('admin.arracoamentos_perfis.create');
    }

    public function storeArracoamentosPerfis(ArracoamentosPerfisCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id'] = session('_filial')->id;

        $insert = ArracoamentosPerfis::create($data);

        if ($insert) {
            return redirect()->route('admin.arracoamentos_perfis')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('nome',      $data['nome'])
        ->with('descricao', $data['descricao'])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editArracoamentosPerfis(int $id)
    {
        $data = ArracoamentosPerfis::find($id);

        return view('admin.arracoamentos_perfis.edit')
        ->with('id',        $data['id'])
        ->with('nome',      $data['nome'])
        ->with('descricao', $data['descricao']);
    }

    public function updateArracoamentosPerfis(ArracoamentosPerfisCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $update = ArracoamentosPerfis::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.arracoamentos_perfis')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeArracoamentosPerfis(int $id)
    {
        $data = ArracoamentosPerfis::find($id);

        if ($data->arracoamentos_esquemas->isNotEmpty()) {
            return redirect()->back()
            ->with('warning', 'Você não pode excluir um perfil de arraçoamento com esquemas associados a ele. Desative o perfil, ou exclua todos os esquemas para realizar a exclusão.');
        }

        $delete = $data->delete();

        if ($delete) {
            return redirect()->back()
            ->with('success', 'Registro excluido com sucesso!');
        } 
        
        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function turnArracoamentosPerfis(int $id)
    {
        $data = ArracoamentosPerfis::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->back()
            ->with('success', 'Perfil de arraçoamento desabilitado com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->back()
        ->with('success', 'Perfil de arraçoamento habilitado com sucesso!');
    }
}
