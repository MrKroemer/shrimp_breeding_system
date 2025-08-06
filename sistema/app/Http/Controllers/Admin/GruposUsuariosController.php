<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GruposUsuariosCreateFormRequest;
use App\Models\GruposUsuarios;

class GruposUsuariosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingGruposUsuarios(int $grupo_id)
    {
        $grupos_usuarios = GruposUsuarios::listing($this->rowsPerPage, [
            'grupo_id' => $grupo_id
        ]);

        return view('admin.grupos.usuarios.list')
        ->with('grupos_usuarios', $grupos_usuarios)
        ->with('grupo_id',        $grupo_id);
    }

    public function searchGruposUsuarios(Request $request, int $grupo_id)
    {
        $formData = $request->except(['_token']);
        $formData['grupo_id'] = $grupo_id;

        $grupos_usuarios = GruposUsuarios::search($formData, $this->rowsPerPage);

        return view('admin.grupos.usuarios.list')
        ->with('formData',        $formData)
        ->with('grupos_usuarios', $grupos_usuarios)
        ->with('grupo_id',        $grupo_id);
    }

    public function storeGruposUsuarios(GruposUsuariosCreateFormRequest $request, int $grupo_id)
    {
        $data = $request->except(['_token']);

        $data['grupo_id']   = $grupo_id;
        $data['usuario_id'] = trim($data['usuarios_usuario']);

        unset($data['usuarios_usuario']);

        $usuarios = GruposUsuarios::where('grupo_id', $data['grupo_id'])
        ->where('usuario_id', $data['usuario_id']);

        if ($usuarios->count() > 0) {
            return redirect()->route('admin.grupos.grupos_usuarios', ['grupo_id' => $grupo_id])
            ->with('warning', 'Este usuário já está registrado neste grupo.');
        }

        $insert = GruposUsuarios::create($data);

        if ($insert) {
            return redirect()->route('admin.grupos.grupos_usuarios', ['grupo_id' => $grupo_id])
            ->with('success', 'Usuário adicionado ao grupo com sucesso!');
        }

        return redirect()->route('admin.grupos.grupos_usuarios', ['grupo_id' => $grupo_id])
        ->with('error', 'Oops! Algo de errado ocorreu ao adicionar o usuário ao grupo. Tente novamente!');
    }

    public function removeGruposUsuarios(int $grupo_id, int $id)
    {
        $data = GruposUsuarios::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.grupos.grupos_usuarios', ['grupo_id' => $grupo_id])
            ->with('success', 'Usuário removido do grupo com sucesso!');
        } 

        return redirect()->route('admin.grupos.grupos_usuarios', ['grupo_id' => $grupo_id])
        ->with('error', 'Ocorreu um erro ao remover o usuário do grupo. Tente novamente!');
    }
}

