<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;
use Carbon\Carbon;
use App\Models\Tanques;
use App\Models\VwMortalidade;

class MortalidadeReport extends mPDF
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
        
        $this->documentTitle = 'Relatório de Mortalidade';

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

    public function MakeDocument(Array $params)
    {   
        $tanques           = $params[0];
        $taxasmortalidades = $params[1];
        $data_inicial = Carbon::createFromFormat('d/m/Y', $params[2]);
        $data_final   = Carbon::createFromFormat('d/m/Y', $params[3]);

        $dias = $data_inicial->diffInDays($data_final) + 1;

        $data_atual = $data_inicial;
        $tr_datas = "";
        $resultados = "";
        $datas = [];
        $somador_por_data = [];
        $somador_por_tanque = [];
        $total_por_data = "";
        $total_por_tanque = 0;
        $total_geral = 0;

        //Monta o cabeçalho com todas as datas do relatório
        for($i = 0; $i<$dias; $i ++) {

            $datas[] = $data_atual->format('Y-m-d'); 
            $tr_datas .= ('<td class="cabecalho">'.$data_atual->format('d/m').'</td>
                        ');
            $data_atual->addDay();
            
        }
        $tr_datas .= ('<td class="total">Total</td></tr>
        ');
        //Monta as linhas do relatório iniciando o tanque e depois data por data
        foreach($tanques as $tanque){
            $total_por_tanque = 0;
            //inicializa a primeiro vetor totalizador por tanque    
            if(! isset($somador_por_tanque[$tanque->sigla])){
                $somador_por_tanque[$tanque->sigla] = 0;   
            }
            
            $testemortalidade = $taxasmortalidades
            ->where('viveiro',$tanque->sigla)
            ->first();

            if($testemortalidade){
            
                $resultados .= ('<tr><td class="viveiros">'.$testemortalidade->viveiro.'</td><td class="viveiros">'.$testemortalidade->ciclo->numero.'</td><td class="viveiros">'.$testemortalidade->ciclo->dias_cultivo().'</td>
                            ');

                foreach($datas as $dt){

                    //inicializa o segundo vetor totalizador por data
                    if(! isset($somador_por_data[$dt])){
                        $somador_por_data[$dt] = 0;
                    }
                    

                    $mortalidade = $taxasmortalidades
                    ->where('viveiro',$tanque->sigla)
                    ->where('data_aplicacao',$dt)
                    ->first();

                    //testa se o arracoamento pro viveiro existe se sim exibe senão coloca uma "-" pra mostrar que não existiu
                    if(isset($mortalidade)){
                        
                        $total_por_tanque += ($mortalidade->mortalidade ?: 0 );
                        $somador_por_data[$dt] += ($mortalidade->mortalidade ?: 0 );
                        is_numeric($mortalidade->mortalidade) ? $resultados .= ('<td>'.$mortalidade->mortalidade.'</td>') : $resultados .= ('<td>-</td>');
                    }else{
                        
                        $total_por_tanque += 0;
                        $somador_por_data[$dt] += 0;
                        $resultados .= ('<td>-</td>
                            ');
                    }
                    
                }
                $total_geral += $total_por_tanque;
                $resultados .= ('<td class="total">'.$total_por_tanque.'</td></tr>
                    ');
               
                
            }
            
        }    
                foreach($somador_por_data as $total_data){
                    $total_por_data .= ('<td class="rodape">'.$total_data.'</td>');
                }
                $total_por_data .= ('<td class="valortotal">'.$total_geral.'</td></tr>');

        $this->documentBody .= ('
        <div class="content">
            <table class="table_01">
                <thead>
                    <tr>
                        <td colspan="' . ($dias + 4) . '" style="border-bottom: 1px solid #000;">Mortalidade de: ' . $params[2] . ' até ' . $params[3] . '</td>
                    
                    </tr>
                    <tr>
                        <td style="width:35px;" class="cabecalho">Viveiros</td>
                        <td style="width:30px;" class="cabecalho">Lote</td>
                        <td style="width:45px;" class="cabecalho">Dias Cultivo</td>
                        '.
                        
                        $tr_datas.'
                    </tr>
                </thead>
                <tbody>
        ');

        $this->documentBody .= $resultados;

        $this->documentBody .= ('
                </tbody> 
                <tfoot>
                    <tr>
                        <td class="rodape">Total</td>
                        <td class="rodape">-</td>
                        <td class="rodape">-</td>
                        '.$total_por_data.'
                    </tr>
                </tfoot>   
            </table>
        </div>
        ');
        //dd($total_por_data);
        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
