<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GruposUsuariosCreateFormRequest;
use App\Models\GruposUsuarios;

class UsuariosGruposController extends Controller
{
    private $rowsPerPage = 10;

    public function listingUsuariosGrupos(int $usuario_id)
    {
        $usuarios_grupos = GruposUsuarios::listing($this->rowsPerPage, [
            'usuario_id' => $usuario_id
        ]);

        return view('admin.usuarios.grupos.list')
        ->with('usuarios_grupos', $usuarios_grupos)
        ->with('usuario_id',      $usuario_id);
    }

    public function searchUsuariosGrupos(Request $request, int $usuario_id)
    {
        $formData = $request->except(['_token']);
        $formData['usuario_id'] = $usuario_id;

        $usuarios_grupos = GruposUsuarios::search($formData, $this->rowsPerPage);

        return view('admin.usuarios.grupos.list')
        ->with('formData',        $formData)
        ->with('usuarios_grupos', $usuarios_grupos)
        ->with('usuario_id',      $usuario_id);
    }

    public function storeUsuariosGrupos(GruposUsuariosCreateFormRequest $request, int $usuario_id)
    {
        $data = $request->except(['_token']);

        $data['usuario_id'] = $usuario_id;
        $data['grupo_id'] = trim($data['grupos_grupo']);

        unset($data['grupos_grupo']);

        $grupos = GruposUsuarios::where('usuario_id', $data['usuario_id'])
        ->where('grupo_id', $data['grupo_id']);

        if ($grupos->count() > 0) {
            return redirect()->back()
            ->with('warning', 'Este usuário já está registrado neste grupo.');
        }

        $insert = GruposUsuarios::create($data);

        if ($insert) {
            return redirect()->back()
            ->with('success', 'Usuário adicionado ao grupo com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Oops! Algo de errado ocorreu ao adicionar o usuário ao grupo. Tente novamente!');
    }

    public function removeUsuariosGrupos(int $grupo_id, int $id)
    {
        $data = GruposUsuarios::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->back()
            ->with('success', 'Usuário removido do grupo com sucesso!');
        } 

        return redirect()->back()
        ->with('error', 'Ocorreu um erro ao remover o usuário do grupo. Tente novamente!');
    }
}

