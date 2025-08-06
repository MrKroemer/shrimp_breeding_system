<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lotes;
use App\Models\LotesBiometrias;
use App\Http\Requests\LotesBiometriasCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class LotesBiometriasController extends Controller
{
    public function createLotesBiometrias(Request $request, int $lote_id)
    {
        $data = $request->only([
            'redirectBack',
            'povoamento_id',
        ]);

        $redirectBack = 'no';
        $povoamento_id = 0;

        if (isset($data['redirectBack'])) {
            $redirectBack  = $data['redirectBack'];
            $povoamento_id = $data['povoamento_id'];
        }

        $lote_biometria = Lotes::find($lote_id)->lote_biometria;

        if ($lote_biometria instanceof LotesBiometrias) {
            return redirect()->route('admin.lotes.biometrias.to_edit', ['id' => $lote_biometria->id, 'lote_id' => $lote_id]);
        }

        return view('admin.lotes.biometrias.create')
        ->with('lote_id',       $lote_id)
        ->with('redirectBack',  $redirectBack)
        ->with('povoamento_id', $povoamento_id);
    }

    public function storeLotesBiometrias(LotesBiometriasCreateFormRequest $request, int $lote_id)
    {
        $data = $request->except(['_token']);

        $redirectBack = 'no';
        $povoamento_id = 0;

        if (isset($data['redirectBack'])) {
            $redirectBack  = $data['redirectBack'];
            $povoamento_id = $data['povoamento_id'];
        }

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);
        
        $data['lote_id'] = $lote_id;
        $data['data_analise'] = date('Y-m-d');
        
        $lote_biometria = LotesBiometrias::create($data);

        if ($lote_biometria instanceof LotesBiometrias) {

            $route = 'admin.lotes';
            $redirect = redirect()->route($route);

            if ($redirectBack == 'yes') {
                $route = 'admin.lotes.redirect_to.povoamentos.to_create';
                $redirect = redirect()->route($route, ['povoamento_id' => $povoamento_id]);
            }

            return $redirect->with('success', 'Registro salvo com sucesso!');

        }

        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editLotesBiometrias(int $lote_id, int $id)
    {
        $data = LotesBiometrias::where('lote_id', $lote_id)->first();

        $lote = Lotes::find($lote_id);

        $povoado = ! is_null($lote->povoamento);

        return view('admin.lotes.biometrias.edit')
        ->with('id',            $id)
        ->with('mm5',           $data['mm5'])
        ->with('mm6',           $data['mm6'])
        ->with('mm7',           $data['mm7'])
        ->with('mm8',           $data['mm8'])
        ->with('mm9',           $data['mm9'])
        ->with('mm10',          $data['mm10'])
        ->with('mm11',          $data['mm11'])
        ->with('mm12',          $data['mm12'])
        ->with('mm13',          $data['mm13'])
        ->with('mm14',          $data['mm14'])
        ->with('mm15',          $data['mm15'])
        ->with('mm16',          $data['mm16'])
        ->with('mm17',          $data['mm17'])
        ->with('mm18',          $data['mm18'])
        ->with('mm19',          $data['mm19'])
        ->with('mm20',          $data['mm20'])
        ->with('total_animais', $data['total_animais'])
        ->with('estresse',      $data['estresse'])
        ->with('sobrevivencia', $data['sobrevivencia'])
        ->with('tamanho_medio', $data['tamanho_medio'])
        ->with('peso_total',    $data['peso_total'])
        ->with('peso_medio',    $data['peso_medio'])
        ->with('lote',          $lote)
        ->with('povoado',       $povoado);
    }

    public function updateLotesBiometrias(LotesBiometriasCreateFormRequest $request, int $lote_id, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

        $update = LotesBiometrias::find($id);

        if ($update->update($data)) {
            return redirect()->back()
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeLotesBiometrias(int $lote_id, int $id)
    {
        $data = LotesBiometrias::find($id);

        if ($data->lote_id == $lote_id) {

            if ($data->delete()) {
                return redirect()->route('admin.lotes')
                ->with('success', 'Registro excluido com sucesso!');
            }

        }

        return redirect()->route('admin.lotes')
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
