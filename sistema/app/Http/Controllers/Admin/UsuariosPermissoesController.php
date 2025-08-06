<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UsuariosPermissoesCreateFormRequest;
use App\Models\Modulos;
use App\Models\Usuarios;
use App\Models\UsuariosFiliais;
use App\Models\UsuariosPermissoes;

class UsuariosPermissoesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingUsuariosPermissoes(int $usuario_id, int $usuario_filial_id)
    {
        $usuarios_permissoes = UsuariosPermissoes::listing($this->rowsPerPage, [
            'usuario_filial_id' => $usuario_filial_id
        ]);

        $usuario        = Usuarios::find($usuario_id);
        $usuario_filial = UsuariosFiliais::find($usuario_filial_id);

        $modulos = Modulos::orderBy('nome')->get();

        return view('admin.usuarios.permissoes.list')
        ->with('usuarios_permissoes', $usuarios_permissoes)
        ->with('usuario_filial',      $usuario_filial)
        ->with('usuario',             $usuario)
        ->with('modulos',             $modulos);
    }

    public function searchUsuariosPermissoes(Request $request, int $usuario_id, int $usuario_filial_id)
    {
        $formData = $request->except(['_token']);
        $formData['usuario_id'] = $usuario_id;

        $usuarios_permissoes = UsuariosPermissoes::search($formData, $this->rowsPerPage);

        $usuario        = Usuarios::find($usuario_id);
        $usuario_filial = UsuariosFiliais::find($usuario_filial_id);

        $modulos = Modulos::orderBy('nome')->get();

        return view('admin.usuarios.permissoes.list')
        ->with('formData',            $formData)
        ->with('usuarios_permissoes', $usuarios_permissoes)
        ->with('usuario_filial',      $usuario_filial)
        ->with('usuario',             $usuario)
        ->with('modulos',             $modulos);
    }

    public function storeUsuariosPermissoes(UsuariosPermissoesCreateFormRequest $request, int $usuario_id, int $usuario_filial_id)
    {
        $data = $request->except(['_token', 'permissoes_opcao']);

        $data['usuario_filial_id'] = $usuario_filial_id;
        $data['modulo_id']         = trim($data['permissoes_modulo']);
        $data['menu_id']           = trim($data['permissoes_item']);
        
        unset($data['permissoes_modulo'], $data['permissoes_item']);

        $permissoes = UsuariosPermissoes::where('usuario_filial_id', $data['usuario_filial_id'])
        ->where('modulo_id', $data['modulo_id'])
        ->where('menu_id',   $data['menu_id']);

        if ($permissoes->count() > 0) {
            return redirect()->route('admin.usuarios.filiais.permissoes', ['usuario_id' => $usuario_id, 'usuario_filial_id' => $usuario_filial_id])
            ->with('warning', 'O usuário já possui essas permissões!');
        }

        $insert = UsuariosPermissoes::create($data);

        if ($insert) {
            return redirect()->route('admin.usuarios.filiais.permissoes', ['usuario_id' => $usuario_id, 'usuario_filial_id' => $usuario_filial_id])
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->route('admin.usuarios.filiais.permissoes', ['usuario_id' => $usuario_id, 'usuario_filial_id' => $usuario_filial_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function updateUsuariosPermissoes(Request $request, int $usuario_id, int $usuario_filial_id, int $id)
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

        $update = UsuariosPermissoes::where('id', $id)->update($data);

        if ($update) {
            return redirect()->back()
            ->with('success', 'Registro atualizado com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de atualizar o registro! Tente novamente.');
    }

    public function removeUsuariosPermissoes(int $usuario_id, int $usuario_filial_id, int $id)
    {
        $data = UsuariosPermissoes::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        } 

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente!');
    }
}
