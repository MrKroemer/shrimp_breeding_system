<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\Ciclos;
use Carbon\Carbon;
//use NumberFormatter;

set_time_limit(0);

class ResumoAnalisesBiometricasReport extends mPDF
{
    private $documentLogo;
    private $documentTitle;
    private $documentHeader;
    private $documentFooter;
    private $documentCss;
    private $documentBody;

    public function __construct()
    {
        parent::__construct([
            'mode'          => 'utf-8', 
            'orientation'   => 'L', 
            'default_font'  => 'Arial',
            'margin_left'   => 2,
            'margin_right'  => 2,
            'margin_top'    => 30,
            'margin_bottom' => 10,
            'margin_header' => 5,
            'margin_footer' => 5,
            'tempDir' => storage_path('tmp'),
        ]);

        $this->documentLogo  = public_path('img/system_logo.png');
        
        $this->documentTitle = 'Relatório de análises biométricas';

        $this->documentHeader = ('
            <table style="width: 100%;">
                <tr>
                    <td style="width: 20%; text-align: left;"><img style="width: 150px;" src="' . $this->documentLogo . '"/></td>
                    <td style="width: 60%; text-align: center; padding-top: 30px;"><h3 style="font-size: 4mm;">' . $this->documentTitle . '</h3></td>
                    <td style="width: 20%; text-align: right;"></td>
                </tr>
            </table>
            <div style="border-bottom: 1px solid #000; margin-top: 5px;"></div>
        ');

        $this->documentFooter = ('
            <div style="width: 100%; border-bottom: 1px solid #000;"></div>
            <table style="width: 100%; font: italic 10px sans-serif;">
                <tr>
                    <td style="width: 50%; text-align: left;">Página: {PAGENO}/{nbpg}</td>
                    <td style="width: 50%; text-align: right;">Emitido em: {DATE d/m/Y H:i:s}</td>
                </tr>
            </table>
        ');

        $this->documentCss = ('
            .content {
                border-top: 1px solid #000;
                border-left: 1px solid #000;
                border-right: 1px solid #000;
                margin-bottom: 2mm;
            }
            table {
                width: 100%;
            }
            .table_01 {
                background-color: #e6e6e6;
            }
            .table_01 td {
                font: 2.5mm Georgia, serif;
                padding-left: 0mm;
                padding-right: 0mm;
                padding-top: 0mm;
                padding-bottom: 0mm;
                text-align: center;
            }
            .table_02 {
                vertical-align: top;
            }
            .table_02 td {
                font: 2.5mm Georgia, serif;
                padding-left: 1mm;
            }
            .cabecalho {
                border-top: 1px solid #000;
                border-bottom: 1px solid #000;
                width: 2.4%;
            }
            .corpo {
                border-right: 1px solid #000;
            }
            .rodape {
                border-top: 1px solid #000;
                border-right: 1px solid #000;
                border-bottom: 1px solid #000;
            }
        ');

        $this->SetTitle($this->documentTitle);
        $this->setHTMLHeader($this->documentHeader);
        $this->setHTMLFooter($this->documentFooter);
    }

    public function MakeDocument(array $param)
    {
        $time1 = microtime(true);

        $ciclos           = $param[0];
        $data_solicitacao = $param[1];
       
        //$dias = $data_inicial->diffInDays($data_final) +1;
 

        $data_exibicao    = $data_solicitacao->format('d/m/Y');
        $data_solicitacao = $data_solicitacao->format('Y-m-d');

       

        $this->documentBody .= '<div class="content">';

        $this->documentBody .= '<table class="table_01" cellpadding="0" cellspacing="0">';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td colspan="39" style="margin-left: 2px;border-bottom: 1px solid #000;text-align:left">Data da Solicitação: ' . $data_solicitacao .'</td>';
        $this->documentBody .= '</tr>';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td colspan="6"  style="border-right: 1px solid #000;">Informações básicas</td>';
        $this->documentBody .= '<td colspan="2"  style="border-right: 1px solid #000;">Dias</td>';
        $this->documentBody .= '<td colspan="17" style="border-right: 1px solid #000;">Resumo das últimas biometrias</td>';
        $this->documentBody .= '<td colspan="11">Estimativas</td>';
        $this->documentBody .= '</tr>';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td class="cabecalho">Viv.</td>';
        $this->documentBody .= '<td class="cabecalho">Área</td>';
        $this->documentBody .= '<td class="cabecalho" style="width:25px;">Pov.</td>';
        $this->documentBody .= '<td class="cabecalho" style="width:7px;">Dens.</td>';
        $this->documentBody .= '<td class="cabecalho" >Lote</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 3.0%; border-right: 1px solid #000;">Gene.</td>';
        $this->documentBody .= '<td class="cabecalho">Prep.</td>';
        $this->documentBody .= '<td class="cabecalho" style="border-right: 1px solid #000;">Cult.</td>';
        $this->documentBody .= '<td class="cabecalho">1</td>';
        $this->documentBody .= '<td class="cabecalho">2</td>';
        $this->documentBody .= '<td class="cabecalho">3</td>';
        $this->documentBody .= '<td class="cabecalho">4</td>';
        $this->documentBody .= '<td class="cabecalho">5</td>';
        $this->documentBody .= '<td class="cabecalho">6</td>';
        $this->documentBody .= '<td class="cabecalho">7</td>';
        $this->documentBody .= '<td class="cabecalho">8</td>';
        $this->documentBody .= '<td class="cabecalho">9</td>';
        $this->documentBody .= '<td class="cabecalho">10</td>';
        $this->documentBody .= '<td class="cabecalho">11</td>';
        $this->documentBody .= '<td class="cabecalho">12</td>';
        $this->documentBody .= '<td class="cabecalho">13</td>';
        $this->documentBody .= '<td class="cabecalho">14</td>';
        $this->documentBody .= '<td class="cabecalho">15</td>';
        $this->documentBody .= '<td class="cabecalho">16</td>';
        $this->documentBody .= '<td class="cabecalho" style="border-right: 1px solid #000;">17</td>';
        //$this->documentBody .= '<td class="cabecalho" style="border-right: 1px solid #000;">20</td>';
        //$this->documentBody .= '<td class="cabecalho" style="width: 3.0%;">P. méd.</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 3.0%;">Cr. Sem.</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 3.0%;">Cr. méd.</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 3.0%;">Cr. 2US.</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 3.0%;">Dens.</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 3.0%;">Sob.</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 5.0%;">Bio.</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 4.0%;">Kg/ha</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 3.0%;">T.C.A.</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 3.0%;">T.C.A.P</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 3.0%;">R$/Kg</td>';
        $this->documentBody .= '<td class="cabecalho" style="width: 7.0%;">Custo Total</td>';
        $this->documentBody .= '<td class="cabecalho">Viv.</td>';
        $this->documentBody .= '</tr>';

        $color_flag = 0;
        $color = array("#ffffff","#e6e6e6");

        $num_peso_medio            = 0;
        $num_crescimento_semanal   = 0;
        $num_crescimento_medio     = 0;
        $num_crescimento_2us       = 0;
        $num_densidade             = 0;
        $num_sobrevivencia         = 0;
        $num_biomassa              = 0;
        $num_kg_hectare            = 0;
        $num_tca                   = 0;
        $num_cons                  = 0;
        $num_custo_kg              = 0;
        $num_custoTotal            = 0;

        $media_peso_medio          = 0;
        $media_crescimento_semanal = 0;
        $media_crescimento_medio   = 0;
        $media_crescimento_2us     = 0;
        $media_densidade           = 0;
        $media_sobrevivencia       = 0;
        $media_biomassa            = 0;
        $media_kg_hectare          = 0;
        $media_tca                 = 0;
        $media_cons                = 0;
        $media_custo_kg            = 0;
        $soma_custoTotal           = 0;

        $teste1 = [];

            
            foreach ($ciclos as $ciclo) {

            $ciclo  = $ciclo->ciclo;
            $tanque = $ciclo->tanque;
            //$situacao = $ciclo->ciclo_situacao;
            $dias_cultivo = $ciclo->dias_cultivo($data_solicitacao);


            $genetica = $ciclo->genetica($data_solicitacao);
            //$especie = $ciclo->lot

            $data_povoamento = 'n/a';

            if (! is_null($ciclo->povoamento)) {
                $data_povoamento = $ciclo->povoamento->data_inicio('d/m/y');

                }
            $racao_consumida = $ciclo->racao_consumida($data_solicitacao); 
            

            $this->documentBody .= '<tr style="background-color:' . $color[$color_flag] . '">';
            
            $color_flag = $color_flag ? 0 : 1;
            
            $white_cells = 0;
            $peso_medio  = 0;
            $genetica = $ciclo->genetica($data_solicitacao);
           
            $this->documentBody .= '<td class="corpo">' . $tanque->sigla . '</td>';
            $this->documentBody .= '<td class="corpo">' . $tanque->hectares() . '</td>';
            $this->documentBody .= '<td class="corpo">' . $data_povoamento  . '</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($ciclo->densidade_inicial(), 0) . '</td>';
            $this->documentBody .= '<td class="corpo">' . $ciclo->numero . '</td>';
            $this->documentBody .= '<td class="corpo">' . $genetica . '</td>';
            $this->documentBody .= '<td class="corpo">' . $ciclo->dias_preparacao() . '</td>';
            $this->documentBody .= '<td class="corpo">' . $dias_cultivo . '</td>';

            /* $white_cells = 0;
            $peso_medio  = 0; */

            $biometrias = $ciclo->analises_biometricas
            ->where('data_analise','<=',$data_solicitacao)
            ->where('situacao', '>', 1)
            ->sortBy('data_analise');

            if ($biometrias->isNotEmpty()) {

                

                foreach ($biometrias as $biometria) {
                    
                    if ($ciclo->dias_cultivo($biometria->data_analise) >= 28){

                        $peso_medio = $biometria->peso_medio();

                        $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($peso_medio) . '</td>';

                        $white_cells ++;

                    }
                }

            }

            while ($white_cells < 17) {
                $this->documentBody .= '<td class="corpo">&nbsp;</td>';
                $white_cells ++;
            }

            if( ( $biometrias->isNotEmpty() ) && ( $dias_cultivo >= 28) ){
                $crescimento_semanal = $ciclo->crescimento_semanal($data_solicitacao);
                $crescimento_medio   = $ciclo->crescimento_medio($data_solicitacao);
                $crescimento_2us     = $ciclo->crescimento_2us($data_solicitacao);
                $densidade           = $ciclo->densidade($data_solicitacao);
                $sobrevivencia       = $ciclo->sobrevivencia($data_solicitacao);
                $biomassa            = $ciclo->biomassa($data_solicitacao);
                $kg_hectare          = $ciclo->kg_hectare($data_solicitacao);
                $tca                 = $ciclo->tca($data_solicitacao);
                $cons                = $ciclo->cons($data_solicitacao);
                $custo_kg            = $ciclo->custo_kg($data_solicitacao);
                $custoTotal          = $ciclo->custo_total($data_solicitacao);
                
            }else{
                $crescimento_semanal = 0;
                $crescimento_medio   = 0;
                $crescimento_2us     = 0;
                $densidade           = 0;
                $sobrevivencia       = 0;
                $biomassa            = 0;
                $kg_hectare          = 0;
                $tca                 = 0;
                $cons                = 0;
                $custo_kg            = 0;
                $custoTotal          = $ciclo->custo_total($data_solicitacao);
            }

            //Acumula o ultimo peso media de cada amostra
            $media_peso_medio += $peso_medio;

            //Acumula a quantidade de biometrias para fazer a média
            if($peso_medio > 0){
                $num_peso_medio ++;
            }

            if($ciclo->tanque->setor_id == 8){
                $custo_saidas = $ciclo->custo_saidas();
                $custoTotal = $custo_saidas;
                $custo_kg = ($biomassa > 0) ? ($custoTotal / $biomassa) : 0;
            }

            //$formatter = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);

            //$this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($peso_medio) . '</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($crescimento_semanal) . '</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($crescimento_medio) . '</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($crescimento_2us) . '</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($densidade) . '</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($sobrevivencia, 0) . '%</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($biomassa, 0) . '</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($kg_hectare, 0) . '</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($tca) . '</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($cons, 2,',',''). '</td>';
            $this->documentBody .= '<td class="corpo">' . DataPolisher::numberFormat($custo_kg) . '</td>';
            $this->documentBody .= '<td class="corpo">' . 'R$ ' . DataPolisher::numberFormat($custoTotal) . '</td>';
            $this->documentBody .= '<td class="corpo">' . $tanque->sigla . '</td>';

            //$teste1[]  = $peso_medio;
            
            $this->documentBody .= '</tr>';

            $num_crescimento_semanal   += (($crescimento_semanal != 0) ? 1 : 0);
            $num_crescimento_medio     += (($crescimento_medio   != 0) ? 1 : 0);
            $num_crescimento_2us       += (($crescimento_2us     != 0) ? 1 : 0);
            $num_densidade             += (($densidade           != 0) ? 1 : 0);
            $num_sobrevivencia         += (($sobrevivencia       != 0) ? 1 : 0);
            $num_biomassa              += (($biomassa            != 0) ? 1 : 0);
            $num_kg_hectare            += (($kg_hectare          != 0) ? 1 : 0);
            $num_tca                   += (($tca                 != 0) ? 1 : 0);
            $num_cons                  += (($cons                != 0) ? 1 : 0);
            $num_custo_kg              += (($custo_kg            != 0) ? 1 : 0);
            $num_custoTotal            += (($num_custoTotal      != 0) ? 1 : 0);

            $media_crescimento_semanal += $crescimento_semanal; 
            $media_crescimento_medio   += $crescimento_medio; 
            $media_crescimento_2us     += $crescimento_2us; 
            $media_densidade           += $densidade;
            $media_sobrevivencia       += $sobrevivencia;
            $media_biomassa            += $biomassa;
            $media_kg_hectare          += $kg_hectare;
            $media_tca                 += $tca;
            $media_cons                += $cons;
            $media_custo_kg            += $custo_kg;
            $soma_custoTotal           += $custoTotal;

        }

        //$teste = "Peso: ".$media_peso_medio." - Quantidade de Ciclos: ".$num_peso_medio;

      /*   if ($num_peso_medio != 0) {
            $media_peso_medio = DataPolisher::numberFormat($media_peso_medio / $num_peso_medio);
        } */

        if ($num_crescimento_semanal != 0) {
            $media_crescimento_semanal = DataPolisher::numberFormat($media_crescimento_semanal / $num_crescimento_semanal);
        }

        if ($num_crescimento_medio != 0) {
            $media_crescimento_medio = DataPolisher::numberFormat($media_crescimento_medio / $num_crescimento_medio);
        }

        if ($num_crescimento_2us != 0) {
            $media_crescimento_2us = DataPolisher::numberFormat($media_crescimento_2us / $num_crescimento_2us);
        }

        if ($num_densidade != 0) {
            $media_densidade = DataPolisher::numberFormat($media_densidade / $num_densidade);
        }

        if ($num_sobrevivencia != 0) {
            $media_sobrevivencia = DataPolisher::numberFormat($media_sobrevivencia / $num_sobrevivencia);
        }

        /* if ($num_biomassa != 0) {
            $media_biomassa = DataPolisher::numberFormat($media_biomassa / $num_biomassa);
        } */

        if ($num_kg_hectare != 0) {
            $media_kg_hectare = DataPolisher::numberFormat($media_kg_hectare / $num_kg_hectare);
        }

        if ($num_tca != 0) {
            $media_tca = DataPolisher::numberFormat($media_tca / $num_tca);
        }
            
        if ($num_cons != 0) {
            $media_cons = DataPolisher::numberFormat($media_cons / $num_cons);
        }

        
        if ($num_custo_kg != 0) {
            $media_custo_kg = $soma_custoTotal / $media_biomassa;

        }

           //$media_custo_kg = $formatter->formatCurrency($media_custo_kg, 'BRL');
           //$num_custo_kg = $formatter->formatCurrency($num_custo_kg, 'BRL');
           //$custoTotal = $formatter->formatCurrency($custoTotal, 'BRL');
           //$soma_custoTotal = $formatter->formatCurrency($soma_custoTotal, 'BRL');
           
          
           
        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td class="rodape" colspan="22"></td>';
        $this->documentBody .= '<td class="rodape" colspan="3">Médias gerais</td>';
        //$this->documentBody .= '<td class="rodape">' . $media_peso_medio . '</td>';
        $this->documentBody .= '<td class="rodape">' . $media_crescimento_semanal . '</td>';
        $this->documentBody .= '<td class="rodape">' . $media_crescimento_medio . '</td>';
        $this->documentBody .= '<td class="rodape">' . $media_crescimento_2us.  '</td>';
        $this->documentBody .= '<td class="rodape">' . $media_densidade . '</td>';
        $this->documentBody .= '<td class="rodape">' . $media_sobrevivencia . '</td>';
        $this->documentBody .= '<td class="rodape">' . DataPolisher::numberFormat($media_biomassa) . '</td>';
        $this->documentBody .= '<td class="rodape">' . $media_kg_hectare . '</td>';
        $this->documentBody .= '<td class="rodape">' . $media_tca . '</td>';
        $this->documentBody .= '<td class="rodape">' . $media_cons . '</td>';
        $this->documentBody .= '<td class="rodape">' . DataPolisher::numberFormat($media_custo_kg) . '</td>';
        $this->documentBody .= '<td class="rodape">' . 'R$ ' . DataPolisher::numberFormat($soma_custoTotal) . '</td>';
        $this->documentBody .= '<td style="border-bottom: 1px solid #000;"></td>';

        $this->documentBody .= '</tr>';

        $this->documentBody .= '</table>';

        $this->documentBody .= '</div>';

        $time2 = microtime(false);

        
        //$this->documentBody .= '<div>' . round($time2 - $time1) . 's</div>';

        /* $this->documentBody .= '<div>' . $teste . '</div>';

        foreach ($teste1 as $tst){
            $this->documentBody .= '<div>' . $tst . '</div>';
        } */

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);

        //dd([$sobrevivencia]);

    }
}
