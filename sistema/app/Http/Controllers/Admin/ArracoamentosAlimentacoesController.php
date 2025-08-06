<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArracoamentosAlimentacoes;
use App\Models\ArracoamentosEsquemas;
use App\Http\Requests\ArracoamentosAlimentacoesCreateFormRequest;

class ArracoamentosAlimentacoesController extends Controller
{
    private $rowsPerPage = 10;

    public function storeArracoamentosAlimentacoes(ArracoamentosAlimentacoesCreateFormRequest $request, int $arracoamento_perfil_id, int $arracoamento_esquema_id)
    {
        $data = $request->except(['_token']);

        $data['porcentagem']          = trim($data['quantidades_porcentagem']);
        $data['alimentacao_inicial']  = trim($data['quantidades_primeira']);
        $data['alimentacao_final']    = trim($data['quantidades_ultima']);
        $data['arracoamento_esquema_id'] = $arracoamento_esquema_id;

        unset($data['quantidades_porcentagem'], $data['quantidades_primeira'], $data['quantidades_ultima']);
        
        $esquema = ArracoamentosEsquemas::where('id', $arracoamento_esquema_id)->first();

        if ($data['porcentagem'] > $esquema->porcentagem_alimentacoes() || $data['porcentagem'] <= 0) {
            return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_esquemas_itens', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id])
            ->with('warning', 'O percentual total das alimentações, não podem exceder 100%.');
        }

        if ($data['alimentacao_inicial'] > $data['alimentacao_final']) {
            return redirect()->back()
            ->with('warning', 'A primeira alimentação do intervalo, não pode estar a frente da última informada.');
        }

        $insert = ArracoamentosAlimentacoes::create($data);

        if ($insert) {
            return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_esquemas_itens', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id])
            ->with('success', 'Alimentação adicionada com sucesso!');
        }

        return redirect()->back()
        ->with('porcentagem',             $data['porcentagem'])
        ->with('alimentacao_inicial',     $data['alimentacao_inicial'])
        ->with('alimentacao_final',       $data['alimentacao_final'])
        ->with('arracoamento_esquema_id', $arracoamento_esquema_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao adicionar a alimentação. Tente novamente!');
    }

    public function removeArracoamentosAlimentacoes(int $arracoamento_perfil_id, int $arracoamento_esquema_id, int $id)
    {
        $data = ArracoamentosAlimentacoes::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_esquemas_itens', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id])
            ->with('success', 'Alimentação removida com sucesso!');
        } 
        
        return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_esquemas_itens', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema_id])
        ->with('error', 'Oops! Algo de errado ocorreu ao remover a alimentação. Tente novamente!');
    }
}
