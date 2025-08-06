<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tanques;
use App\Models\TanquesTipos;
use App\Models\VwCiclosPorSetor;
use App\Models\VwBalancoIonico;
use App\Models\VwColetasParametrosPorTanque;
use App\Models\VwCalcicasPorTanque;
use App\Models\VwMagnesicasPorTanque;
use Carbon\Carbon;

class ResumoAnalisesAguaController extends Controller
{
    public function createResumoAnalisesAgua()
    {
        $tanques_tipos = TanquesTipos::orderBy('nome')
        ->get();
        
        return view('reports.resumo_analises_agua.create')
        ->with('tanques_tipos', $tanques_tipos);
        
    }

    public function generateResumoAnalisesAgua(Request $request)
    {
        $data = $request->except(['_token']);

        if (empty($data['data_aplicacao'])) {
            return redirect()->back()
            ->with('warning', 'Informe uma data para gerar a análise.');
        }
        

        $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao'])->format('Y-m-d');
        
        if(isset($data['tanque_tipo_id'])){
            $tanques = Tanques::where('filial_id', session('_filial')->id)
            ->where('tanque_tipo_id', $data['tanque_tipo_id'])
            ->where('situacao','ON')
            ->orderBy('sigla')
            ->get();
        }else{
            $tanques = Tanques::where('filial_id', session('_filial')->id)
            ->where('situacao','ON')
            ->orderBy('sigla')
            ->get();
        }
        
        $analises = [];
        
        foreach ($tanques as $tanque){

            //Inicialização de váriaveis
            $calcio = 0;
            $dureza_calcio = 0;
            $dureza_magnesio = 0;
            $dureza_total = 0;
            $dias_cultivo = "0";
            $np = 0;
            $mg = 0;
            $k = 0;
            
            //Pega os ultimos 3 balanços ionicos desse tnque
            $balanco_ionico = VwBalancoIonico::where('tanque_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->first();

            /* //Pega as três ultimas análises magnesicas desse tanque
            $magnesio = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 17)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->limit(3)
            ->get(); */


            //Pega as três ultimas analises de floco
            $floco = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 2)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->limit(3)
            ->get();

            //Pega as três ultimas analises de Salinidade
            $salinidade = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 3)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->limit(3)
            ->get();

            //Pega as três ultimas analises de Alcalinidade
            $alcalinidade = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 9)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->limit(3)
            ->get();

            //Pega as três ultimas análises de amonia
            $amonia = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 4)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->limit(3)
            ->get();

            //Pega as três ultimas análises de nitrito
            $nitrito = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 11)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->limit(3)
            ->get();

            //Pega as três ultimas análises de nitrato
            $nitrato = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 13)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->limit(3)
            ->get();

            //Pega as três ultimas análises de Fósforo
            $fosforo = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 15)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->limit(3)
            ->get();

            //Pega as três ultimas análises de Sílica
            $silica = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 16)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->limit(2)
            ->get();

            //Pega a ultima análises de Magnesio
            $magnesio = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 17)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->first();

            //Pega a ultima análises de Potassio
            $potassio = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 10)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->first();

            //Pega a ultima análises de Dureza Total
            $dureza_total = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 25)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->first();

            //Pega o ultimo nitrato
            $ultimo_nitrato = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 13)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->first();

            //Pega o ultimo Fósforo
            $ultimo_fosforo = VwColetasParametrosPorTanque::where('coletas_parametros_tipos_id', 15)
            ->where('tnq_id',$tanque->id)
            ->where('data_coleta','<=' ,$data_aplicacao)
            ->orderByDesc('data_coleta')
            ->first();

            
            if($tanque->tanque_tipo_id == 1){
                $dias_cultivo = $tanque->ultimo_ciclo()->dias_cultivo();
            }

            
            //Calcula proporção de Nitrogênio/Fósforo
            if(is_object($ultimo_nitrato) && is_object($ultimo_fosforo)){
                if(($ultimo_nitrato->valor() > 0) && ($ultimo_fosforo->cpa_valor > 0)){
                    $np = round($ultimo_nitrato->valor()/$ultimo_fosforo->cpa_valor);
                }
            }

            //Balanço Iônico

            if($balanco_ionico){

                $calcio = $balanco_ionico->calcio;
            
                $dureza_calcio = $balanco_ionico->dureza_calcio;

                $dureza_magnesio = $balanco_ionico->dureza_magnesio;

                $dureza_total = $balanco_ionico->dureza_total;

            }

            
            //Calcula proporção de Calcio:Magnésio:Potássio
            if(is_object($magnesio) && ($calcio > 0)){
                if(($magnesio->valor() > 0) && ($calcio > 0)){
                    $mg = $magnesio->valor() / $calcio;
                    $mg = round( $mg, 1 );
                }
            }


            if(is_object($potassio) && ($calcio > 0)){
                if(($potassio->valor() > 0) && ($calcio > 0)){
                    $k = $potassio->valor() / $calcio;
                    $k = round( $k, 1 );
                }
            }

            $ca_mg_k = '1:'.$mg.':'.$k;

            $analises[$tanque->id] = [

                'calcio'          => $calcio,
                'dureza_calcio'   => $dureza_calcio,
                'dureza_total'    => $dureza_total,
                'flocos'          => $floco->reverse(),
                'salinidades'     => $salinidade->reverse(),
                'alcalinidades'   => $alcalinidade->reverse(),
                'amonias'         => $amonia->reverse(),
                'nitritos'        => $nitrito->reverse(),
                'nitratos'        => $nitrato->reverse(),
                'fosforos'        => $fosforo->reverse(),
                'silicas'         => $silica->reverse(),
                'magnesio'        => $magnesio,
                'dureza_magnesio' => $dureza_magnesio,
                'potassio'        => $potassio,
                'np'              => $np,
                'ca_mg_k'         => $ca_mg_k,
                'ultimo_nitrato'  => $ultimo_nitrato,
                'ultimo_fosforo'  => $ultimo_fosforo,
                'dias_cultivo'    => $dias_cultivo,

            ];

        }

        $document = new ResumoAnalisesAguaReport;
        
        $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao']);

        $document->MakeDocument([$tanques, $analises,  $data_aplicacao]);
        
        $fileName = 'resumo_analises_agua_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
