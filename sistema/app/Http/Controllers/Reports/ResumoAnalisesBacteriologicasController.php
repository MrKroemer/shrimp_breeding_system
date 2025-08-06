<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setores;
use App\Models\Tanques;
use App\Models\AnalisesLaboratoriaisTipos;
use App\Models\VwCiclosPorSetor;
use App\Models\VwBacteriologicasPorTanque;
use Carbon\Carbon;

class ResumoAnalisesBacteriologicasController extends Controller
{
    public function createResumoAnalisesBacteriologicas()
    {
        $setores = Setores::where('filial_id', session('_filial')->id)->get();

        $analises = AnalisesLaboratoriaisTipos::where('id', '<', 4)->get();
        
        return view('reports.resumo_analises_bacteriologicas.create')
        ->with('analises',                $analises)
        ->with('setores',                 $setores);
        
    }

    public function generateResumoAnalisesBacteriologicas(Request $request)
    {
        $data = $request->except(['_token']);
        
        $analise_laboratorial = [];
        $analise_laboratorial = explode(',',$data['analise_laboratorial']);

        $analise_laboratorial_tipo_id  = $analise_laboratorial[0];

        if (empty($data['data_analise'])) {
            return redirect()->back()
            ->with('warning', 'Informe uma data para gerar a análise.');
        }


        $data_analise = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-m-d');
        
        $tanques = Tanques::where('setor_id', $data['setor_id'])
        ->orderBy('sigla')
        ->get();

        //dd($tanques);

        $analises = [];
        $datas = [];
        
        foreach ($tanques as $tanque){

            //Pega as ultimas 10 amostras amarelas opacas 1mm
            $bacterias = VwBacteriologicasPorTanque::where('tanque_id',$tanque->id)
            ->where('analise_laboratorial_tipo_id', $analise_laboratorial_tipo_id)
            ->where('data_analise','<=' ,$data_analise)
            ->orderByDesc('data_analise')
            ->limit(10)
            ->get();

            //dd($bacterias);
            //Pega as ultimas 10 datas
            $datas = VwBacteriologicasPorTanque::where('tanque_id',$tanque->id)
            ->where('analise_laboratorial_tipo_id', $analise_laboratorial_tipo_id)
            ->where('data_analise','<=' ,$data_analise)
            ->orderByDesc('data_analise')
            ->limit(10)
            ->get(['data_analise']);

           

            $analises[$tanque->id] =  $bacterias;
            

        }

        $document = new ResumoAnalisesBacteriologicasReport;
        
        $data_analise = Carbon::createFromFormat('d/m/Y', $data['data_analise']);
        
        $document->MakeDocument([$tanques, $analises,  $data_analise, $datas, $analise_laboratorial]);
        
        $fileName = 'resumo_analises_bacteriologicas_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
