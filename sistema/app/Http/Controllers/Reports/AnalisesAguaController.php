<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AnalisesLaboratoriais;
use App\Models\Setores;
use App\Models\AplicacoesInsumos;
use App\Models\VwColetasParametrosPorTanque;
use Carbon\Carbon;

class AnalisesAguaController extends Controller
{
    public function createAnalisesAgua()
    {
        $setores = Setores::where('filial_id', session('_filial')->id)->get();
        
        return view('reports.analises_agua.create')
        ->with('setores',                 $setores);
        
    }

    public function generateAnalisesAgua(Request $request)
    {
        $data = $request->except(['_token']);

        if (empty($data['data_aplicacao'])) {
            return redirect()->back()
            ->with('warning', 'Informe uma data para gerar a análise.');
        }

        if (empty($data['setor_id'])) {
            return redirect()->back()
            ->with('warning', 'Informe um Setor para gerar a análise.');
        }

        $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao'])->format('Y-m-d');
        
        $tanques = Tanques::where('setor_id', $data['setor_id'])
        ->wherewhere('tanque_tipo_id', 1)
        ->get();

        $analises = [];

        foreach ($tanques as $tanque){
            
            //Pega as duas ultimas bacteriologias de água desse tanque
            $bacteriologia = VwBacteriologicasPorTanque::where('id',$tanque->id)
            ->where('analise_laboratorial_tipo_id','<=' , 3)
            ->where('data_analise','<=' ,$data_aplicacao)
            ->orderByDesc('data_analise')
            ->limit(2)
            ->get();

            //Pega as duas ultimas analises Calcicas desse tanque
            $calcio = VwBacteriologicasPorTanque::where('id',$tanque->id)
            ->where('analise_laboratorial_tipo_id','<=' , 4)
            ->where('data_analise','<=' ,$data_aplicacao)
            ->limit(1)
            ->get();

            //Pega as três ultimas analises de floco
            $floco = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 2)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->limit(3)
            ->get();

            //Pega as três ultimas analises de Salinidade
            $salinidade = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 3)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->limit(3)
            ->get();

            //Pega as três ultimas analises de Alcalinidade
            $alcalinidade = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 9)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->limit(3)
            ->get();

            //Pega as três ultimas análises de amonia
            $amonia = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 4)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->limit(3)
            ->get();

            //Pega as três ultimas análises de nitrito
            $nitrito = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 11)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->limit(3)
            ->get();

            //Pega as três ultimas análises de nitrato
            $nitrato = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 13)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->limit(3)
            ->get();

            //Pega as três ultimas análises de Fósforo
            $fosforo = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 15)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->limit(3)
            ->get();

            //Pega as três ultimas análises de Sílica
            $silica = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 16)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->limit(3)
            ->get();

            //Pega a ultima análises de Magnesio
            $magnesio = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 17)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->limit(1)
            ->get();

            //Pega a ultima análises de Potassio
            $potassio = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 10)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->limit(1)
            ->get();



            $analises[$tanque->id] = [

                'bacteriologias' => $bacteriologia,
                'calcios'        => $calcio,
                'flocos'         => $floco,
                'salinidades'    => $salinidade,
                'alcalinidades'  => $alcalinidade,
                'amonias'        => $amonia,
                'nitritos'       => $nitrito,
                'nitratos'       => $nitrato,
                'fosforos'       => $fosforo,
                'silicas'        => $silica,
                'magnesios'      => $magnesio,
                'potassios'      => $potassio,
            ];

        }

        $document = new AnalisesAguaReport;
        
        $document->MakeDocument([$tanques, $analises, $data_aplicacao]);
        
        $fileName = 'analises_agua_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
