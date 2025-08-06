<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GruposPermissoesCreateFormRequest;
use App\Models\Modulos;
use App\Models\Grupos;
use App\Models\GruposFiliais;
use App\Models\GruposPermissoes;

class GruposPermissoesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingGruposPermissoes(int $grupo_id, int $grupo_filial_id)
    {
        $grupos_permissoes = GruposPermissoes::listing($this->rowsPerPage, [
            'grupo_filial_id' => $grupo_filial_id
        ]);

        $grupo        = Grupos::find($grupo_id);
        $grupo_filial = GruposFiliais::find($grupo_filial_id);

        $modulos = Modulos::orderBy('nome')->get();

        return view('admin.grupos.permissoes.list')
        ->with('grupos_permissoes', $grupos_permissoes)
        ->with('grupo_filial',      $grupo_filial)
        ->with('grupo',             $grupo)
        ->with('modulos',           $modulos);
    }

    public function searchGruposPermissoes(Request $request, int $grupo_id, int $grupo_filial_id)
    {
        $formData = $request->except(['_token']);
        $formData['grupo_id'] = $grupo_id;

        $grupos_permissoes = GruposPermissoes::search($formData, $this->rowsPerPage);

        $grupo        = Grupos::find($grupo_id);
        $grupo_filial = GruposFiliais::find($grupo_filial_id);

        $modulos = Modulos::orderBy('nome')->get();

        return view('admin.grupos.permissoes.list')
        ->with('formData',          $formData)
        ->with('grupos_permissoes', $grupos_permissoes)
        ->with('grupo_filial',      $grupo_filial)
        ->with('grupo',             $grupo)
        ->with('modulos',           $modulos);
    }

    public function storeGruposPermissoes(GruposPermissoesCreateFormRequest $request, int $grupo_id, int $grupo_filial_id)
    {
        $data = $request->except(['_token', 'permissoes_opcao']);

        $data['grupo_filial_id']  = $grupo_filial_id;
        $data['modulo_id']        = trim($data['permissoes_modulo']);
        $data['menu_id']          = trim($data['permissoes_item']);
        
        unset($data['permissoes_modulo'], $data['permissoes_item']);

        $permissoes = GruposPermissoes::where('grupo_filial_id', $data['grupo_filial_id'])
        ->where('modulo_id', $data['modulo_id'])
        ->where('menu_id',   $data['menu_id']);

        if ($permissoes->count() > 0) {
            return redirect()->route('admin.grupos.filiais.permissoes', ['grupo_id' => $grupo_id, 'grupo_filial_id' => $grupo_filial_id])
            ->with('warning', 'O grupo já possui essas permissões!');
        }

        $insert = GruposPermissoes::create($data);

        if ($insert) {
            return redirect()->route('admin.grupos.filiais.permissoes', ['grupo_id' => $grupo_id, 'grupo_filial_id' => $grupo_filial_id])
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->route('admin.grupos.filiais.permissoes', ['grupo_id' => $grupo_id, 'grupo_filial_id' => $grupo_filial_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function updateGruposPermissoes(Request $request, int $grupo_id, int $grupo_filial_id, int $id)
    {
        $data = $request->except(['_token']);

        $permissoes = '';

        if (isset($data['create']) && $data['create'] == 'on') {
            $permissoes .= empty($permissoes) ? 'C' : '|C';
        }
        if (isset($data['read']) && $data['read'] == 'on') {
            $permissoes .= empty($permissoes) ? 'R' : '|R';
        }
        if (isset($data['update']) && $data['update'] == 'on') {
            $permissoes .= empty($permissoes) ? 'U' : '|U';
        }
        if (isset($data['delete']) && $data['delete'] == 'on') {
            $permissoes .= empty($permissoes) ? 'D' : '|D';
        }

        unset($data['create'], $data['read'], $data['update'], $data['delete']);

        $data['permissoes'] = $permissoes;

        $update = GruposPermissoes::where('id', $id)->update($data);

        if ($update) {
            return redirect()->back()
            ->with('success', 'Registro atualizado com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de atualizar o registro! Tente novamente.');
    }

    public function removeGruposPermissoes(int $grupo_id, int $grupo_filial_id, int $id)
    {
        $data = GruposPermissoes::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        } 

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente!');
    }
}
