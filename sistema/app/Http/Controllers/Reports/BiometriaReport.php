<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;

class BiometriaReport extends mPDF
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
            'margin_left'   => 5,
            'margin_right'  => 5,
            'margin_top'    => 30,
            'margin_bottom' => 10,
            'margin_header' => 5,
            'margin_footer' => 5,
            'tempDir' => storage_path('tmp'),
        ]);

        $this->documentLogo  = public_path('img/system_logo.png');
        
        $this->documentTitle = 'Relatório de Biometria';

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
                border: 1px solid #000;
                margin-bottom: 2mm;
            }
            table {
                width: 100%;
            }
            .table_01 {
                background-color: #e6e6e6;
                border-bottom: 1px solid #000;
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
                border-bottom: 1px solid #000;
            }

            .rodape {
                border-top: 1px solid #000;
            }

            .viveiros {
                border-right: 1px solid #000;
            }

            .total {
                border-left: 1px solid #000;
                border-bottom: 1px solid #000;
            }

            .valortotal {
                border-left: 1px solid #000;
                border-top: 1px solid #000;
            }

        ');

        $this->SetTitle($this->documentTitle);
        $this->setHTMLHeader($this->documentHeader);
        $this->setHTMLFooter($this->documentFooter);
    }

    public function MakeDocument($param)
    {   
        $ciclos = $param;
        $resultados = "";
        $cor = array("#ffffff","#e6e6e6");
        
        $this->documentBody .= ('
        <div class="content">
            <table class="table_01" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <td colspan="5" style="border: 1px solid #000;">Informações Básicas</td>
                        <td colspan="2" style="border: 1px solid #000;">Dias</td>
                        <td colspan="25" style="border: 1px solid #000;">Peso Médio / Resumo de Biometrias</td>
                        <td colspan="4" style="border: 1px solid #000;">Valores Atuais</td>
                        <td colspan="7" style="border: 1px solid #000;">Valores Estimados</td>
                    </tr>
                    <tr>
                        <td width="20" class="cabecalho">Viv.</td>
                        <td width="40" class="cabecalho">Area Ha.</td>
                        <td width="40" class="cabecalho">Data Pov</td>
                        <td class="cabecalho">Dens.</td>
                        <td class="cabecalho">Lote</td>
                        <td class="cabecalho">Prep.</td>
                        <td class="cabecalho">Cult.</td>
                        <td class="cabecalho">1</td>
                        <td class="cabecalho">2</td>
                        <td class="cabecalho">3</td>
                        <td class="cabecalho">4</td>
                        <td class="cabecalho">5</td>
                        <td class="cabecalho">6</td>
                        <td class="cabecalho">7</td>
                        <td class="cabecalho">8</td>
                        <td class="cabecalho">9</td>
                        <td class="cabecalho">10</td>
                        <td class="cabecalho">11</td>
                        <td class="cabecalho">12</td>
                        <td class="cabecalho">13</td>
                        <td class="cabecalho">14</td>
                        <td class="cabecalho">15</td>
                        <td class="cabecalho">16</td>
                        <td class="cabecalho">17</td>
                        <td class="cabecalho">18</td>
                        <td class="cabecalho">19</td>
                        <td class="cabecalho">20</td>
                        <td class="cabecalho">21</td>
                        <td class="cabecalho">22</td>
                        <td class="cabecalho">23</td>
                        <td class="cabecalho">24</td>
                        <td class="cabecalho">25</td>
                        <td class="cabecalho">Peso</td>
                        <td class="cabecalho">Cresc.</td>
                        <td class="cabecalho">C.Médio</td>
                        <td class="cabecalho">C.2US</td>
                        <td class="cabecalho">Sbv.</td>
                        <td class="cabecalho">Biomassa.</td>
                        <td class="cabecalho">KG/ha</td>
                        <td class="cabecalho">TCA</td>
                        <td class="cabecalho">Cons.</td>
                        <td class="cabecalho">Custo</td>
                        <td class="cabecalho">Viveiro</td>
                    </tr>
                </thead>
                <tbody>
        ');
        //Informações de viveiros
        $cont = 0;

        $num_peso_medio          = 0;
        $num_crescimento_semanal = 0;
        $num_crescimento_medio   = 0;
        $num_crescimento_2us     = 0;
        $num_sobrevivencia       = 0;
        $num_biomassa            = 0;
        $num_kg_ha               = 0;
        $num_tca                 = 0;
        $num_cons                = 0;
        $num_custo_kg            = 0;

        $media_peso_medio          = 0;
        $media_crescimento_semanal = 0;
        $media_crescimento_medio   = 0;
        $media_crescimento_2us     = 0;
        $media_sobrevivencia       = 0;
        $media_biomassa            = 0;
        $media_kg_ha               = 0;
        $media_tca                 = 0;
        $media_cons                = 0;
        $media_custo_kg            = 0;

        foreach($ciclos as $ciclo){

            $biometrias = "";
            $contbio = 0;
            $crescmedio = [];
            $cm = 0;
            /*
            1  = Peso Medio
            2  = Crescimento Semanal
            3  = Crescimento Medio
            4  = Crescimento 2us
            5  = Sobrevivencia
            6  = Biomassa
            7  = Kg_HA
            8  = TCA
            9  = Cons
            10 = Custo
            */
            $medias = [];

            if ($ciclo->ciclo->biometrias()->isNotEmpty()) {

                $num_peso_medio ++;

                foreach($ciclo->ciclo->biometrias()->sortBy('data_analise') as $biometria){
                    
                    $biometrias .= '<td>'.DataPolisher::numberFormat($biometria->peso_medio()).'</td>';
                    $contbio ++;

                    $media_peso_medio += $biometria->peso_medio();

                }

            } 
            
            while($contbio < 25){
                $biometrias .= "<td>&nbsp;</td>";
                $contbio++;
            }

            $resultados .= ('<tr style="background-color:'.$cor[$cont].'" >
                <td>'.$ciclo->ciclo->tanque->sigla.'</td>
                <td>'.$ciclo->ciclo->tanque->hectares().'</td>
                <td>'. (! is_null($ciclo->ciclo->povoamento) ? $ciclo->ciclo->povoamento->data_inicio('d/m/Y') : 'n/a') .'</td>
                <td>'.DataPolisher::numberFormat($ciclo->ciclo->densidade(),0).'</td>
                <td>'.$ciclo->ciclo->numero.'</td>
                <td>'.$ciclo->ciclo->dias_preparacao().'</td>
                <td>'.$ciclo->ciclo->dias_cultivo().'</td>
            ');
            
            $resultados .= $biometrias;

            $resultados .= ('
                <td>'.DataPolisher::numberFormat($biometria->peso_medio(),2).'</td>
                <td>'.DataPolisher::numberFormat($ciclo->ciclo->crescimento_semanal(),2).'</td>
                <td>'.DataPolisher::numberFormat($ciclo->ciclo->crescimento_medio(),2).'</td>
                <td>'.DataPolisher::numberFormat($ciclo->ciclo->crescimento_2us(),2).'</td>
                <td>'.$ciclo->ciclo->sobrevivencia().'%</td>
                <td>'.DataPolisher::numberFormat($ciclo->ciclo->biomassa(),0).'</td>
                <td>'.DataPolisher::numberFormat($ciclo->ciclo->kg_ha(),0).'</td>
                <td>'.$ciclo->ciclo->tca().'</td>
                <td>'.$ciclo->ciclo->cons().'%</td>
                <td>R$ '.round($ciclo->ciclo->custo_kg(),2).'</td>
                <td>'.$ciclo->ciclo->tanque->sigla.' </td>
                </tr>
            ');
            if($cont == 0){
                $cont = 1;
            }else{
                $cont = 0;
            }

            $num_crescimento_semanal += ($ciclo->ciclo->crescimento_semanal() ? 1 : 0);
            $num_crescimento_medio   += ($ciclo->ciclo->crescimento_medio() ? 1 : 0);
            $num_crescimento_2us     += ($ciclo->ciclo->crescimento_2us() ? 1 : 0);
            $num_sobrevivencia       += ($ciclo->ciclo->sobrevivencia() ? 1 : 0);
            $num_biomassa            += ($ciclo->ciclo->biomassa() ? 1 : 0);
            $num_kg_ha               += ($ciclo->ciclo->kg_ha() ? 1 : 0);
            $num_tca                 += ($ciclo->ciclo->tca() ? 1 : 0);
            $num_cons                += ($ciclo->ciclo->cons() ? 1 : 0);
            $num_custo_kg            += ($ciclo->ciclo->custo_kg() ? 1 : 0);

            $media_crescimento_semanal += $ciclo->ciclo->crescimento_semanal(); 
            $media_crescimento_medio   += $ciclo->ciclo->crescimento_medio(); 
            $media_crescimento_2us     += $ciclo->ciclo->crescimento_2us(); 
            $media_sobrevivencia       += $ciclo->ciclo->sobrevivencia();
            $media_biomassa            += $ciclo->ciclo->biomassa();
            $media_kg_ha               += $ciclo->ciclo->kg_ha();
            $media_tca                 += $ciclo->ciclo->tca(); 
            $media_cons                += $ciclo->ciclo->cons();
            $media_custo_kg            += $ciclo->ciclo->custo_kg();
            
        }

        if(($media_peso_medio) && ($num_peso_medio)){
            $media_peso_medio = DataPolisher::numberFormat($media_peso_medio / $num_peso_medio);
        }else{
            $media_peso_medio = 0;
        }
        if(($media_crescimento_semanal) && ($num_crescimento_semanal)){
            $media_crescimento_semanal = DataPolisher::numberFormat($media_crescimento_semanal / $num_crescimento_semanal);
        }else{
            $media_crescimento_semanal = 0;
        }
        if(($media_crescimento_medio) && ($num_crescimento_medio)){
            $media_crescimento_medio = DataPolisher::numberFormat($media_crescimento_medio / $num_crescimento_medio);
        }else{
            $media_crescimento_medio = 0;
        }
        if(($media_crescimento_2us) && ($num_crescimento_2us)){
            $media_crescimento_2us = DataPolisher::numberFormat($media_crescimento_2us / $num_crescimento_2us);
        }else{
            $media_crescimento_2us = 0;
        }
        if(($media_sobrevivencia) && ($num_sobrevivencia)){
            $media_sobrevivencia = DataPolisher::numberFormat($media_sobrevivencia / $num_sobrevivencia);
        }else{
            $media_sobrevivencia = 0;
        }
        if(($media_biomassa) && ($num_biomassa)){
            $media_biomassa = DataPolisher::numberFormat($media_biomassa / $num_biomassa);
        }else{
            $media_biomassa = 0;
        }
        if(($media_kg_ha) && ($num_kg_ha)){
            $media_kg_ha = DataPolisher::numberFormat($media_kg_ha / $num_kg_ha);
        }else{
            $media_kg_ha = 0;
        }
        if(($media_tca) && ($num_tca)){
            $media_tca = DataPolisher::numberFormat($media_tca / $num_tca);
        }else{
            $media_tca = 0;
        }
        if(($media_cons) && ($num_cons)){
            $media_cons = DataPolisher::numberFormat($media_cons / $num_cons);
        }else{
            $media_cons = 0;
        }
        if(($media_custo_kg) && ($num_custo_kg)){
            $media_custo_kg = DataPolisher::numberFormat($media_custo_kg / $num_custo_kg);
        }else{
            $media_custo_kg = 0;
        }
        //dd($media_peso_medio,$media_crescimento_semanal,$media_crescimento_medio,$media_crescimento_2us,$media_sobrevivencia,$media_biomassa,$media_kg_ha,$media_tca,$media_cons,$media_custo_kg);
        $this->documentBody .= $resultados;

        $this->documentBody .= ('
                </tbody> 
                <tfoot>
                    <tr>
                        <td colspan="31">Média Dos Valores Listados</td>
                        <td class="rodape">'.$media_peso_medio.'</td>
                        <td class="rodape">'.$media_crescimento_semanal.'</td>
                        <td class="rodape">'.$media_crescimento_medio.'</td>
                        <td class="rodape">'.$media_crescimento_2us.'</td> 
                        <td class="rodape">'.$media_sobrevivencia.'</td>
                        <td class="rodape">'.$media_biomassa.'</td>
                        <td class="rodape">'.$media_kg_ha.'</td>
                        <td class="rodape">'.$media_tca.'</td>
                        <td class="rodape">'.$media_cons.'</td>
                        <td class="rodape">'.$media_custo_kg.'</td>
                        <td class="rodape"></td>
                        <td class="rodape"></td>
                    </tr>
                </tfoot>   
            </table>
        </div
        ');
        //dd($total_por_data);
        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
