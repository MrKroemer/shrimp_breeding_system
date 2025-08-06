<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResumoAnalisesBiometricasReportCreateFormRequest;
use App\Models\VwCiclosPorSetor;
use Carbon\Carbon;
use App\Models\Ciclos;

set_time_limit(0);

class ResumoAnalisesBiometricasController extends Controller
{
    public function createBiometria()
    {
        return view('reports.analises_biometricas.create');
    }

    public function generateBiometria(ResumoAnalisesBiometricasReportCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $ciclos = VwCiclosPorSetor::where('ciclo_inicio','<=', $data['data_solicitacao'])
        ->where('filial_id', session('_filial')->id)
        ->where('ciclo_situacao', '<=', 8) //Listar todos os ciclos.
        ->where(function ($query) use ($data) { 
            $query->whereNull('ciclo_fim')->orWhere('ciclo_fim', '>=', $data['data_solicitacao']);
        })
        ->where('ciclo_tipo', 1)
        ->orderBy('tanque_sigla')
        ->get();
                
        $data_solicitacao = Carbon::createFromFormat('d/m/Y', $data['data_solicitacao']);

        $document = new ResumoAnalisesBiometricasReport;

        $document->MakeDocument([$ciclos, $data_solicitacao]);

        $fileName = 'analises_biometricas_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
