<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\CustosPorCicloReport;
use App\Http\Requests\CustosPorCicloReportCreateFormRequest;
use App\Models\Ciclos;
use Carbon\Carbon;

class CustosPorCicloController extends Controller
{
    public function createCustosPorCiclo()
    {
        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->whereBetween('situacao', [1, 8]) // Fertilização, Povoamento, Engorda, Despesca, Encerrado
        ->orderBy('numero', 'desc')
        ->get();

        return view('reports.custos_por_ciclo.create')
        ->with('ciclos', $ciclos);
    }

    public function generateCustosPorCiclo(CustosPorCicloReportCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $ciclos = Ciclos::whereIn('id', $data['ciclos'])
        ->get();

        $data_solicitacao = Carbon::createFromFormat('d/m/Y', $data['data_solicitacao']);

        $document = new CustosPorCicloReport;

        $document->MakeDocument([$ciclos, $data_solicitacao]);

        $fileName = 'custos_por_ciclo_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
