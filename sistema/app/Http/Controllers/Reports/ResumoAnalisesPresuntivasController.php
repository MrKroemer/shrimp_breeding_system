<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResumoAnalisesPresuntivasReportCreateFormRequest;
use App\Models\VwCiclosPorSetor;
use App\Models\Setores;
use Carbon\Carbon;

class ResumoAnalisesPresuntivasController extends Controller
{
    public function createResumoAnalisesPresuntivas()
    {
        $setores = Setores::where('filial_id', session('_filial')->id)
        ->orderBy('nome')
        ->get();

        return view('reports.resumo_analises_presuntivas.create')
        ->with('setores', $setores);
    }

    public function generateResumoAnalisesPresuntivas(ResumoAnalisesPresuntivasReportCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $ciclos = VwCiclosPorSetor::where('ciclo_tipo', 1) // Cultivo de camarões
        ->whereBetween('ciclo_situacao', [2, 7]) // Todos, exceto vazios e encerrados
        ->where('ciclo_inicio','<=', $data['data_solicitacao'])
        ->where(function ($query) use ($data) { 
            $query->whereNull('ciclo_fim')->orWhere('ciclo_fim', '>', $data['data_solicitacao']);
        });

        if (isset($data['setores'])) {
            $ciclos->whereIn('setor_id', $data['setores']);
        }

        $ciclos = $ciclos->orderBy('tanque_sigla')
        ->get();

        $data_solicitacao = Carbon::createFromFormat('d/m/Y', $data['data_solicitacao']);

        $document = new ResumoAnalisesPresuntivasReport;

        $document->MakeDocument([$ciclos, $data_solicitacao]);

        $fileName = 'resumo_analises_presuntivas_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
