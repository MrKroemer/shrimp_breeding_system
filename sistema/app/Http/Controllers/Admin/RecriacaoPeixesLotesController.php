<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecriacaoPeixesLotesController extends Controller
{
    public function create(int $lote_peixes_id)
    {
        $lote = LotesPeixes::find($lote_peixes_id);

        if ($lote->tipo == 2) { // Reprodução
            return redirect()->back();
        }

        $lotes_peixes = LotesPeixesOvos::where('lote_peixes_id', $lote_peixes_id);

        $ovos = $lotes_peixes->orderBy('id', 'desc')->get();

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->whereIn('tanque_tipo_id', [8, 9])
        ->whereNotIn('id', $lotes_peixes->get(['tanque_origem_id']))
        ->orderBy('sigla')
        ->get();

        return view('admin.lotes_peixes.ovos.create')
        ->with('lote_peixes_id', $lote_peixes_id)
        ->with('ovos',           $ovos)
        ->with('tanques',        $tanques);
    }

    public function store(LotesPeixesOvosFormRequest $request, int $lote_peixes_id)
    {
        $data = $request->except('_token');

        $data['lote_peixes_id'] = $lote_peixes_id;

        $ovos = LotesPeixesOvos::create($data);

        if ($ovos instanceof LotesPeixesOvos) {
            return redirect()->back();
            // ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function remove(int $lote_peixes_id, int $id)
    {
        $data = LotesPeixesOvos::where('lote_peixes_id', $lote_peixes_id)
        ->where('id', $id)
        ->first();

        if ($data->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        }
        
        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
