<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenusCreateFormRequest;
use App\Models\Menus;
use App\Http\Resources\MenuOpcoesJsonResource;
use App\Http\Resources\MenuItensJsonResource;

class MenusController extends Controller
{
    private $rowsPerPage = 10;

    public function listingMenus(int $modulo_id)
    {
        $menus = Menus::listing($this->rowsPerPage, [ 
            'tipo' => 'menu',
            'modulo_id' => $modulo_id,
        ]);

        return view('admin.menus.list')
        ->with('menus',       $menus)
        ->with('modulo_id',   $modulo_id);
    }

    public function searchMenus(Request $request, int $modulo_id)
    {
        $formData = $request->except(['_token']);
        $formData['modulo_id'] = $modulo_id;

        $menus = Menus::search($formData, $this->rowsPerPage);

        return view('admin.menus.list')
        ->with('formData',    $formData)
        ->with('menus',       $menus)
        ->with('modulo_id',   $modulo_id);
    }

    public function createMenus(int $modulo_id)
    {
        return view('admin.menus.create')
        ->with('modulo_id',   $modulo_id);
    }

    public function storeMenus(MenusCreateFormRequest $request, int $modulo_id)
    {
        $data = $request->except(['_token', 'modulo_nome']);

        $data['nome']      = trim($data['nome']);
        $data['icone']     = trim($data['icone']);
        $data['tipo']      = 'menu';
        $data['modulo_id'] = $modulo_id;

        if (empty($data['icone'])) {
            $data['icone'] = null;
        }

        $insert = Menus::create($data);

        if ($insert) {
            return redirect()->route('admin.modulos.menus', ['modulo_id' => $modulo_id])
            ->with('success', 'Opção de menu cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('nome',      $data['nome'])
        ->with('icone',     $data['icone'])
        ->with('modulo_id', $modulo_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o item de menu. Tente novamente!');
    }

    public function editMenus(int $modulo_id, int $id)
    {
        $data = Menus::find($id);

        return view('admin.menus.edit')
        ->with('id',          $data['id'])
        ->with('nome',        $data['nome'])
        ->with('tipo',        $data['tipo'])
        ->with('rota',        $data['rota'])
        ->with('icone',       $data['icone'])
        ->with('modulo_id',   $modulo_id);
    }

    public function updateMenus(MenusCreateFormRequest $request, int $modulo_id, int $id)
    {
        $data = $request->except(['_token', 'modulo_nome']);

        $data['nome']      = trim($data['nome']);
        $data['icone']     = trim($data['icone']);

        if (empty($data['icone'])) {
            $data['icone'] = null;
        }

        $update = Menus::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.modulos.menus', ['modulo_id' => $modulo_id])
            ->with('success', 'Opção de menu atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id',        $id)
        ->with('modulo_id', $modulo_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o item de menu. Tente novamente!');
    }

    public function removeMenus(int $modulo_id, int $id)
    {
        $data = Menus::find($id);

        if ($data->submenus()->count() > 0) {
            return redirect()->route('admin.modulos.menus', ['modulo_id' => $modulo_id])
            ->with('warning', 'Esta opção de menu não pode ser excluida, existem itens de menu vinculados a ela.');
        }

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.modulos.menus', ['modulo_id' => $modulo_id])
            ->with('success', 'Opção de menu excluido com sucesso!');
        }
        
        return redirect()->route('admin.modulos.menus', ['modulo_id' => $modulo_id])
        ->with('error', 'Ocorreu um erro ao excluir o item de menu. Tente novamente!');
    }

    public function turnMenus(int $modulo_id, int $id)
    {
        $data = Menus::find($id);
        
        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.modulos.menus', ['modulo_id' => $modulo_id])
            ->with('success', 'Opção de menu desabilitado com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.modulos.menus', ['modulo_id' => $modulo_id])
        ->with('success', 'Opção de menu habilitado com sucesso!');
    }

    public function getJsonOpcoes(int $modulo_id)
    {
        $menus_opcoes = Menus::where('modulo_id', $modulo_id)
        ->where('tipo', 'menu')
        ->orderBy('id', 'asc')
        ->get(['id', 'nome']);

        return MenuOpcoesJsonResource::collection($menus_opcoes);
    }

    public function getJsonItens(int $menu_id)
    {
        $menus_itens = Menus::where('menu_id', $menu_id)
        ->where('tipo', 'submenu')
        ->where('situacao', 'ON')
        ->orderBy('id', 'asc')
        ->get(['id', 'nome']);
               
        return MenuItensJsonResource::collection($menus_itens);
    }
}
