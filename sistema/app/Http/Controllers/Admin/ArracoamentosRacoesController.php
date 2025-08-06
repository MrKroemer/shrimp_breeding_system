<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArracoamentosRacoes;
use App\Models\ArracoamentosEsquemas;
use App\Http\Requests\ArracoamentosRacoesCreateFormRequest;

class ArracoamentosRacoesController extends Controller
{
    private $rowsPerPage = 10;

    public function storeArracoamentosRacoes(ArracoamentosRacoesCreateFormRequest $request, int $arracoamento_perfil_id, int $arracoamento_esquema_id)
    {
        $data = $request->except(['_token']);

        $data['porcentagem'] = trim($data['racoes_porcentagem']);
        $data['produto_id']  = trim($data['racoes_produto']);
        $data['arracoamento_esquema_id'] = $arracoamento_esquema_id;

        unset($data['racoes_porcentagem'], $data['racoes_produto']);
        
        $esquema = ArracoamentosEsquemas::where('id', $arracoamento_esquema_id)->first();

        if ($data['porcentagem'] > $esquema->porcentagem_racaos() || $data['porcentagem'] <= 0) {
            return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_esquemas_itens', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id])
            ->with('warning', 'O percentual total de rações, não pode exceder 100%.');
        }

        $insert = ArracoamentosRacoes::create($data);

        if ($insert) {
            return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_esquemas_itens', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id])
            ->with('success', 'Ração adicionada com sucesso!');
        }

        return redirect()->back()
        ->with('porcentagem',             $data['porcentagem'])
        ->with('produto_id',              $data['produto_id'])
        ->with('arracoamento_esquema_id', $arracoamento_esquema_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao adicionar a ração. Tente novamente!');
    }

    public function removeArracoamentosRacoes(int $arracoamento_perfil_id, int $arracoamento_esquema_id, int $id)
    {
        $data = ArracoamentosRacoes::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_esquemas_itens', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id])
            ->with('success', 'Ração removida com sucesso!');
        } 
        
        return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_esquemas_itens', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id])
        ->with('error', 'Oops! Algo de errado ocorreu ao remover a ração. Tente novamente!');
    }
}
