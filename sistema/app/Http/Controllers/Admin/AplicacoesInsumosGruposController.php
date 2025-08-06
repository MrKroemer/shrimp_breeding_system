<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setores;
use App\Models\AplicacoesInsumos;
use App\Models\AplicacoesInsumosGrupos;
use App\Models\AplicacoesInsumosGruposTanques;
use Validator;

class AplicacoesInsumosGruposController extends Controller
{
    public function listingAplicacoesInsumosGrupos()
    {
        $aplicacoes_insumos_grupos = AplicacoesInsumosGrupos::listing([
            'filial_id' => session('_filial')->id,
        ]);

        return view('admin.aplicacoes_insumos_grupos.list')
        ->with('aplicacoes_insumos_grupos', $aplicacoes_insumos_grupos);
    }

    public function searchAplicacoesInsumosGrupos(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $aplicacoes_insumos_grupos = AplicacoesInsumosGrupos::search($formData);

        return view('admin.aplicacoes_insumos_grupos.list')
        ->with('aplicacoes_insumos_grupos', $aplicacoes_insumos_grupos);
    }

    public function createAplicacoesInsumosGrupos()
    {
        $setores = Setores::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'desc')
        ->get();

        return view('admin.aplicacoes_insumos_grupos.create')
        ->with('setores', $setores);
    }

    public function storeAplicacoesInsumosGrupos(Request $request)
    {
        $data = $request->except(['_token']);

        $rules = [
            'nome' => 'required',
        ];

        $messages = [
            'nome.required' => 'O campo "Nome" deve ser informada.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $data['filial_id'] = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        $grupo = AplicacoesInsumosGrupos::create($data);

        if ($grupo instanceof AplicacoesInsumosGrupos) {

            $delete = AplicacoesInsumosGruposTanques::where('aplicacao_insumo_grupo_id', $grupo->id);

            if ($delete->count() != 0) {
                $delete->delete();
            }

            foreach ($data as $key => $value) {

                if (strpos($key, 'tanque_') === 0) {

                    $tanque_id = str_replace('tanque_', '', $key);

                    $delete = AplicacoesInsumosGruposTanques::where('tanque_id', $tanque_id);

                    if ($delete->count() != 0) {
                        $delete->delete();
                    }

                    $create = AplicacoesInsumosGruposTanques::create([
                        'tanque_id' => $tanque_id,
                        'aplicacao_insumo_grupo_id' => $grupo->id,
                    ]);

                    if ($create instanceof AplicacoesInsumosGruposTanques) {

                        $update = AplicacoesInsumos::where('tanque_id', $tanque_id);
                        
                        if ($update->count() != 0) {
                            $update->update(['aplicacao_insumo_grupo_id' => $grupo->id]);
                        }

                        continue;

                    }

                    return redirect()->back()
                    ->with('error', 'Ocorreu um erro! Não foi possível registrar todos os tanques selecionados');
                }

            }

            return redirect()->route('admin.aplicacoes_insumos_grupos')
            ->with('success', 'Registro salvo com sucesso!');

        }

        return redirect()->route('admin.aplicacoes_insumos_grupos')
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editAplicacoesInsumosGrupos(int $id)
    {
        $aplicacao_insumo_grupo = AplicacoesInsumosGrupos::find($id);

        $setores = Setores::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'desc')
        ->get();

        return view('admin.aplicacoes_insumos_grupos.edit')
        ->with('aplicacao_insumo_grupo', $aplicacao_insumo_grupo)
        ->with('setores',                $setores);
    }

    public function updateAplicacoesInsumosGrupos(Request $request, int $id)
    {
        $data = $request->except(['_token']);

        $rules = [
            'nome' => 'required',
        ];

        $messages = [
            'nome.required' => 'O campo "Nome" deve ser informada.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['usuario_id'] = auth()->user()->id;

        $grupo = AplicacoesInsumosGrupos::find($id);

        if ($grupo->update($data)) {

            $delete = AplicacoesInsumosGruposTanques::where('aplicacao_insumo_grupo_id', $grupo->id);

            if ($delete->count() != 0) {
                $delete->delete();
            }

            foreach ($data as $key => $value) {

                if (strpos($key, 'tanque_') === 0) {

                    $tanque_id = str_replace('tanque_', '', $key);

                    $delete = AplicacoesInsumosGruposTanques::where('tanque_id', $tanque_id);

                    if ($delete->count() != 0) {
                        $delete->delete();
                    }

                    $create = AplicacoesInsumosGruposTanques::create([
                        'tanque_id' => $tanque_id,
                        'aplicacao_insumo_grupo_id' => $grupo->id,
                    ]);

                    if ($create instanceof AplicacoesInsumosGruposTanques) {

                        $update = AplicacoesInsumos::where('tanque_id', $tanque_id);
                        
                        if ($update->count() != 0) {
                            $update->update(['aplicacao_insumo_grupo_id' => $grupo->id]);
                        }

                        continue;

                    }

                    return redirect()->back()
                    ->with('error', 'Ocorreu um erro! Não foi possível registrar todos os tanques selecionados');
                }

            }

            return redirect()->back()
            ->with('success', 'Registro salvo com sucesso!');

        }

        return redirect()->route('admin.aplicacoes_insumos_grupos')
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeAplicacoesInsumosGrupos(int $id)
    {
        $aplicacao_insumo_grupo = AplicacoesInsumosGrupos::find($id);

        if ($aplicacao_insumo_grupo->tanques->isEmpty()) {

            if ($aplicacao_insumo_grupo->delete()) {

                return redirect()->back()
                ->with('success', 'Registro excluído com sucesso!');

            }

        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
