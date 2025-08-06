<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmenusCreateFormRequest;
use App\Models\Menus;

class SubmenusController extends Controller
{
    private $rowsPerPage = 10;

    public function listingSubmenus(int $modulo_id, int $menu_id)
    {
        $submenus = Menus::listing($this->rowsPerPage, [ 
            'modulo_id' => $modulo_id, 
            'menu_id'   => $menu_id, 
            'tipo'      => 'submenu'
        ]);

        return view('admin.submenus.list')
        ->with('submenus',  $submenus)
        ->with('menu_id',   $menu_id)
        ->with('modulo_id', $modulo_id);
    }

    public function searchSubmenus(Request $request, int $modulo_id, int $menu_id)
    {
        $formData = $request->except(['_token']);
        $formData['modulo_id'] = $modulo_id;
        $formData['menu_id']   = $menu_id;

        $submenus = Menus::search($formData, $this->rowsPerPage);

        return view('admin.submenus.list')
        ->with('formData',  $formData)
        ->with('submenus',  $submenus)
        ->with('menu_id',   $menu_id)
        ->with('modulo_id', $modulo_id);
    }

    public function createSubmenus(int $modulo_id, int $menu_id)
    {
        return view('admin.submenus.create')
        ->with('menu_id',   $menu_id)
        ->with('modulo_id', $modulo_id);
    }

    public function storeSubmenus(SubmenusCreateFormRequest $request, int $modulo_id, int $menu_id)
    {
        $data = $request->except(['_token']);

        $data['nome']      = trim($data['nome']);
        $data['rota']      = trim($data['rota']);
        $data['icone']     = trim($data['icone']);
        $data['tipo']      = 'submenu';
        $data['menu_id']   = $menu_id;
        $data['modulo_id'] = $modulo_id;

        if (empty($data['icone'])) {
            unset($data['icone']);
        }

        $insert = Menus::create($data);

        if ($insert) {
            return redirect()->route('admin.modulos.submenus', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id])
            ->with('success', 'Item de submenu cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('nome',      $data['nome'])
        ->with('rota',      $data['rota'])
        ->with('icone',     $data['icone'])
        ->with('menu_id',   $menu_id)
        ->with('modulo_id', $modulo_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o item de submenu. Tente novamente!');
    }

    public function editSubmenus(int $modulo_id, int $menu_id, int $id)
    {
        $data = Menus::find($id);

        return view('admin.submenus.edit')
        ->with('id',          $id)
        ->with('nome',        $data['nome'])
        ->with('rota',        $data['rota'])
        ->with('icone',       $data['icone'])
        ->with('menu_id',     $menu_id)
        ->with('modulo_id',   $modulo_id);
    }

    public function updateSubmenus(SubmenusCreateFormRequest $request, int $modulo_id, int $menu_id, int $id)
    {
        $data = $request->except(['_token']);

        $data['nome']  = trim($data['nome']);
        $data['rota']  = trim($data['rota']);
        $data['icone'] = trim($data['icone']);

        if (empty($data['icone'])) {
            unset($data['icone']);
        }

        $update = Menus::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.modulos.submenus', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id])
            ->with('success', 'Item de submenu atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id',        $id)
        ->with('menu_id',   $menu_id)
        ->with('modulo_id', $modulo_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o item de submenu. Tente novamente!');
    }

    public function removeSubmenus(int $modulo_id, int $menu_id, int $id)
    {
        $data = Menus::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.modulos.submenus', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id])
            ->with('success', 'Item de submenu excluido com sucesso!');
        }
        
        return redirect()->route('admin.modulos.submenus', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id])
        ->with('error', 'Ocorreu um erro ao excluir o item de submenu. Tente novamente!');
    }

    public function turnSubmenus(int $modulo_id, int $menu_id, int $id)
    {
        $data = Menus::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.modulos.submenus', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id])
            ->with('success', 'Item de submenu desabilitado com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.modulos.submenus', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id])
        ->with('success', 'Item de submenu habilitado com sucesso!');
    }
}
