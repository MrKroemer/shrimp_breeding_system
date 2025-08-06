<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UsuariosCreateFormRequest;
use App\Http\Requests\UsuariosEditFormRequest;
use App\Models\Usuarios;
use App\Http\Controllers\Util\DataPolisher;

class UsuariosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingUsuarios()
    {
        $usuarios = Usuarios::listing($this->rowsPerPage);

        return view('admin.usuarios.list')
        ->with('usuarios', $usuarios);
    }
    
    public function searchUsuarios(Request $request)
    {
        $formData = $request->except(['_token']);

        $usuarios = Usuarios::search($formData, $this->rowsPerPage);

        return view('admin.usuarios.list')
        ->with('formData', $formData)
        ->with('usuarios', $usuarios);
    }

    public function createUsuarios()
    {
        return view('admin.usuarios.create');
    }

    public function storeUsuarios(UsuariosCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $password = md5($data['password']);

        $data = DataPolisher::toPolish($data, ['RM_EMPTY_STRING', 'TO_LOWERCASE']);

        $data['nome'] = ucwords($data['nome']);
        $data['password'] = bcrypt($password);

        unset($data['imagem']);

        /**
         * Salva o arquivo em '/storage/app/public/images/users/<username>.png'
         * com link simbólico para '/public/storage/images/users/<username>.png'
         */
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            $data['imagem'] = $request->imagem->storeAs('images/users', "{$data['username']}.png");
        }

        $usuario = Usuarios::create($data);

        if ($usuario instanceof Usuarios) {
            return redirect()->route('admin.usuarios')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editUsuarios(int $id)
    {
        $data = Usuarios::find($id);

        return view('admin.usuarios.edit')
        ->with('id',       $id)
        ->with('nome',     $data['nome'])
        ->with('email',    $data['email'])
        ->with('username', $data['username'])
        ->with('imagem',   $data['imagem']);
    }

    public function updateUsuarios(UsuariosEditFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $password = md5($data['password']);

        $data = DataPolisher::toPolish($data, ['RM_EMPTY_STRING', 'TO_LOWERCASE']);

        $data['nome'] = ucwords($data['nome']);

        unset($data['imagem']);

        /**
         * Salva o arquivo em '/storage/app/public/images/users/<username>.png'
         * com link simbólico para '/public/storage/images/users/<username>.png'
         */
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            $data['imagem'] = $request->imagem->storeAs('images/users', "{$data['username']}.png");
        }

        if (isset($data['password'])) {
            $data['password'] = bcrypt($password);
        }

        $usuario = Usuarios::find($id);

        if ($usuario->update($data)) {
            return redirect()->route('admin.usuarios')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function updateUsuariosImagem(Request $request, int $id)
    {
        $data = $request->only(['imagem']);

        $usuario = Usuarios::find($id);

        /**
         * Salva o arquivo em '/storage/app/public/images/users/<username>.png'
         * com link simbólico para '/public/storage/images/users/<username>.png'
         */
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            $data['imagem'] = $request->imagem->storeAs('images/users', "{$usuario->username}.png");
        }

        if ($usuario->update($data)) {
            return redirect()->back()
            ->with('success', 'Imagem alterada com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar a imagem! Tente novamente.');
    }

    public function removeUsuarios(int $id)
    {
        $data = Usuarios::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.usuarios')
            ->with('success', 'Usuário excluido com sucesso!');
        } 

        return redirect()->route('admin.usuarios')
        ->with('error', 'Ocorreu um erro ao excluir o usuário. Tente novamente!');
    }

    public function turnUsuarios(int $id)
    {
        $data = Usuarios::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.usuarios')
            ->with('success', 'Usuário desabilitado com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.usuarios')
        ->with('success', 'Usuário habilitado com sucesso!');
    }
}
