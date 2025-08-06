<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RelAnaliseBiometricaReportCreateFormRequest;
use App\Models\VwCiclosPorSetor;
use Carbon\Carbon;
use App\Models\Ciclos;

set_time_limit(0);

class RelAnaliseBiometricaController extends Controller
{
    public function createRelatorio(){
        return view('reports.relatorio_biometria.create');
    }

    public function generateBiometria(RelAnaliseBiometricaReportCreateFormRequest $request){
        $data = $request->except(['_token']);

        $ciclos = VwCiclosPorSetor::where('ciclo_inicio', '<=', $data['data_solicictacao'])
        ->where('filial_id', session('_filial')->id)
        ->where('cilco_situacao', '<=', 8) //listar todos os ciclos
        ->where(function ($query) use ($data){
            $query->whereNull('ciclo_fim')->orWhere('ciclo_fim', '>=', $data['data_solicitacao']);
        })
        ->where('ciclo_tipo', 1)//Cultivo de camarões
        ->get();

        $data_solicitacao = Carbon::createFromFormat('d/m/y', $data['data_solicitação']);
        $document =  new RelAnaliseBiometricaReport;
        $document->MakeDocument([$ciclos, $data_solicitacao]);
        $filename = 'analises_biometricas' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $document->Output($filename, 'I');
    }
}
