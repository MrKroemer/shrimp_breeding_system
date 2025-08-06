<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;

class ResumoAnalisesBacteriologicasReport extends mPDF
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
        
        $this->documentTitle = 'Relatório de Análises Bacteriologicas';

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
            .table_01 {
                background-color: #e6e6e6;
                border-bottom: 1px solid #000;
            }
            .table_01 td {
                font: 2.5mm Georgia, serif;
                padding-left: 1mm;
            }
            .table_02 {
                vertical-align: top;
                border: 1px solid #000;
                margin-bottom: 2mm;
            }
            .table_02 td {
                font: 2.5mm Georgia, serif;
                padding-left: 1mm;
                border: 1px solid #000;
                margin-bottom: 2mm;
            }
        ');
        $this->SetTitle($this->documentTitle);
        $this->setHTMLHeader($this->documentHeader);
        $this->setHTMLFooter($this->documentFooter);
    }

    public function MakeDocument(Array $params)
    {   
      
        $tanques = $params[0];
        $analises= $params[1];
        $data_aplicacao = $params[2];  
        $datas = $params[3];         
        $analise_laboratorial = $params[4];  

        $analise_laboratorial_tipo_id  = $analise_laboratorial[0];
        $analise_laboratorial_nome     = $analise_laboratorial[1];

        
        $this->documentBody .= ('
        <div class="content">
            <table class="table_01" style="width:100%;">
                <tr>
                    <td style="width:40%;">Tipo da Análise: ' . $analise_laboratorial_nome  . '</td>
                    <td style="width:30%;">Data de Referência: ' . $data_aplicacao->format('d/m/Y') . '</td>
                    <td style="width:30%;">Emitido por: ' . auth()->user()->nome . '</td>
                </tr>
            </table>
            <table class="table_02" style="width:100%;">
                <thead>
                    <tr>
                        <td rowspan="2"  style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFFFFF; text-align:left; width:10%;" align="center" valign="middle"><strong>Viveiro</strong></td>
                        <td rowspan="2"  style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFFFFF; text-align:left; width:10%;" align="center" valign="middle"><strong>Bactérias</strong></td>
                        <td colspan="10" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFFFFF; text-align:center; width:80%;"><strong>Data</strong></td>
                    </tr>
            
              
            ');
            $this->documentBody .= ('
                
            <tr>
            ');
       
        $td = 0;
        foreach ($datas as $data){
            $this->documentBody .= ('
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFFFFF; text-align:center;" >'.$data->data_analise().'</td>');
            $td += 1;
        }
        if($td < 10){
            for($i=$td;$i<10;$i++){
                $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFFFFF;" >&nbsp;</td>'); 
            }
        }  
        $this->documentBody .= ('
            </tr>
            </thead>
            <tbody>  
            ');      
        
        foreach ($tanques as $tanque){

        
            $this->documentBody .= ('
                   <tr>
            ');
            $this->documentBody .= ('
            <td rowspan="12" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFFFFF; text-align:center; font-size; 40px; vertical-align: middle;">'.$tanque->sigla.'</td>
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">A.opaca1mm</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->amarela_opaca_mm1 : $bacterias->amarela_opaca_mm1()).'</td>');
                        $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">A.opaca2mm</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->amarela_opaca_mm2 : $bacterias->amarela_opaca_mm2()).'</td>');
                        $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C; text-align:left;">A.opaca3mm</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->amarela_opaca_mm3 : $bacterias->amarela_opaca_mm3()).'</td>');
                        $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">A.opaca4mm</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->amarela_opaca_mm4 : $bacterias->amarela_opaca_mm4()).'</td>');
                        $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">A.opaca > 4mm</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->amarela_opaca_mm5 : $bacterias->amarela_opaca_mm5()).'</td>');
                        $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">Soma de Amarelas</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->amarela_soma_hepato() : $bacterias->amarela_soma()).'</td>');
                        $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #F0E68C ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #99CC32 ; text-align:left;">Amarela Esverdeada</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #99CC32 ; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->amarela_esverdeada : $bacterias->amarela_esverdeada()).'</td>');
            $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #99CC32 ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #32CD99; text-align:left;">Verde</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #32CD99; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->verde : $bacterias->verde()).'</td>');
            $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                    $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #32CD99; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #00BFFF ; text-align:left;">Azul</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #00BFFF ; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->azul : $bacterias->azul()).'</td>');
            $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                    $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #00BFFF ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #D9D9F3 ; text-align:left;">Translucida</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                $this->documentBody .= ('
                <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #D9D9F3 ; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->translucida : $bacterias->translucida()).'</td>');
            $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                    $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #D9D9F3 ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');

            $this->documentBody .= ('
            <tr>
            <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #A9A9A9 ; text-align:left;">Preta</td>
            ');
            $td = 0;
            foreach ($analises[$tanque->id] as $bacterias){
                $this->documentBody .= ('
                <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #A9A9A9 ; text-align:center;">'.($analise_laboratorial_tipo_id == 1 ? $bacterias->preta : $bacterias->preta()).'</td>');
            $td += 1;
            }
            if($td < 10){
                for($i=$td;$i<10;$i++){
                    $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #A9A9A9 ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $this->documentBody .= ('
            </tr>
            ');
            
            
    }

                $this->documentBody .= ('
                </tbody>');
                
        $this->documentBody .= ('
            </table>
        </div>
                ');
                //dd($this->documentBody);
                $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
                $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
