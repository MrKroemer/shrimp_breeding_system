<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UsuariosFiliaisCreateFormRequest;
use App\Models\Filiais;
use App\Models\Usuarios;
use App\Models\UsuariosFiliais;

class UsuariosFiliaisController extends Controller
{
    private $rowsPerPage = 10;

    public function listingUsuariosFiliais(int $usuario_id)
    {
        $usuarios_filiais = UsuariosFiliais::listing($this->rowsPerPage, [
            'usuario_id' => $usuario_id
        ]);

        $usuario = Usuarios::find($usuario_id);

        $filiais = Filiais::orderBy('nome')->get();

        return view('admin.usuarios.filiais.list')
        ->with('usuarios_filiais', $usuarios_filiais)
        ->with('usuario',          $usuario)
        ->with('filiais',          $filiais);
    }

    public function searchUsuariosFiliais(Request $request, int $usuario_id)
    {
        $formData = $request->except(['_token']);
        $formData['usuario_id'] = $usuario_id;

        $usuarios_filiais = UsuariosFiliais::search($formData, $this->rowsPerPage);

        $usuario = Usuarios::find($usuario_id);

        $filiais = Filiais::orderBy('nome')->get();

        return view('admin.usuarios.filiais.list')
        ->with('formData',         $formData)
        ->with('usuarios_filiais', $usuarios_filiais)
        ->with('usuario',          $usuario)
        ->with('filiais',          $filiais);
    }

    public function storeUsuariosFiliais(UsuariosFiliaisCreateFormRequest $request, int $usuario_id)
    {
        $data = $request->except(['_token']);

        $data['usuario_id'] = $usuario_id;

        $filiais = UsuariosFiliais::where('usuario_id', $data['usuario_id'])
        ->where('filial_id', $data['filial_id']);

        if ($filiais->count() > 0) {
            return redirect()->route('admin.usuarios.filiais', ['usuario_id' => $usuario_id])
            ->with('warning', 'O usuário já está associado a esta filial!');
        }

        $insert = UsuariosFiliais::create($data);

        if ($insert) {
            return redirect()->route('admin.usuarios.filiais', ['usuario_id' => $usuario_id])
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->route('admin.usuarios.filiais', ['usuario_id' => $usuario_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeUsuariosFiliais(int $usuario_id, int $id)
    {
        $data = UsuariosFiliais::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.usuarios.filiais', ['usuario_id' => $usuario_id])
            ->with('success', 'Registro excluído com sucesso!');
        } 

        return redirect()->route('admin.usuarios.filiais', ['usuario_id' => $usuario_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente!');
    }

    public function turnUsuariosFiliais(int $usuario_id, int $id)
    {
        $data = UsuariosFiliais::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.usuarios.filiais', ['usuario_id' => $usuario_id])
            ->with('success', 'Filial desabilitada com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.usuarios.filiais', ['usuario_id' => $usuario_id])
        ->with('success', 'Filial habilitado com sucesso!');
    }
}
