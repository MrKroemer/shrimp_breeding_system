<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;

class ResumoAnalisesAguaReport extends mPDF
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
        
        $this->documentTitle = 'Relatório de Análises de Água';

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
            .alcalinidade {
                background-color: #ffffcc; 
                border-right: 1px solid #000; 
                border-bottom: 1px solid #000; 
                background-color: #ffffcc ; 
                text-align:left;
            }
            .salinidade {
                background-color: #ffcccc;
            }
            .floco {
                background-color: #cfe7f5;
            }
            .amonia {
                background-color: #ccffcc;
            }
            .nitrito {
                background-color: #ff9999;
            }
            .nitrato {
                background-color: #ffcc99;
            }
            .fosforo {
                background-color: #ccffcc;
            }
            .np {
                background-color: #ffcccc;
            }
            .silica {
                background-color: #ccffcc;
            }
            .calcio {
                background-color: #ffcccc;
            }
            .camgk {
                background-color: #ffcccc;
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

       // dd($analises);
        $this->documentBody .= ('
        <div class="content">
            <table class="table_01">
                <tr>
                    <td style="width:60%;">Data de Referência: ' . $data_aplicacao->format('d/m/Y') . '</td>
                    <td style="width:40%;">Emitido por: ' . auth()->user()->nome . '</td>
                </tr>
            </table>
            <table class="table_02">
                <thead>
                    <tr>
                        <td align="center" valign="middle"><strong>Viveiro</strong></td>
                        <td align="center" valign="middle"><strong>Cultivo</strong></td>
                        <td colspan="3"     style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffcc ; text-align:left;" align="center" valign="middle"><strong>Alcalinidade</strong></td>
                        <td colspan="3"     style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #cfe7f5 ; text-align:left;" align="center" valign="middle"><strong>Floco</strong></td>
                        <td colspan="3"     style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;" align="center" valign="middle"><strong>Salinidade</strong></td>
                        <td colspan="3"     style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" align="center" valign="middle"><strong>NH3</strong></td>
                        <td colspan="3"     style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ff9999 ; text-align:left;" align="center" valign="middle"><strong>NO2</strong></td>
                        <td colspan="3"     style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcc99 ; text-align:left;" align="center" valign="middle"><strong>NO3</strong></td>
                        <td colspan="3"     style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" align="center" valign="middle"><strong>PO4</strong></td>
                        <td valign="middle" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;" align="center"><strong>N:P</strong></td>
                        <td colspan="2"     style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" align="center" valign="middle"><strong>Sílica</strong></td>
                        <td width="40"      style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;" align="center" align="center" valign="middle"><strong>Dr T</strong></td>
                        <td width="40"      style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;" align="center" align="center" valign="middle"><strong>Dr CA</strong></td>
                        <td width="40"      style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;" align="center" align="center" valign="middle"><strong>Dr MG</strong></td>
                        <td align="center"  style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;" valign="middle"><strong>Ca</strong></td>
                        <td align="center"  style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;" valign="middle"><strong>Mg</strong></td>
                        <td align="center"  style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;" valign="middle"><strong>K</strong></td>
                        <td align="center"  style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;" valign="middle"><strong>Ca:Mg:K</strong></td>
                        <td align="center" valign="middle"><strong>Viveiro</strong></td>
                    </tr>
                    <tr>
                        <td align="center" valign="middle">Siglas</td>
                        <td align="center" valign="middle">Dias</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffcc ; text-align:left;" valign="middle">1ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffcc ; text-align:left;" valign="middle">2ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffcc ; text-align:left;" valign="middle">3ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #cfe7f5 ; text-align:left;" valign="middle">1ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #cfe7f5 ; text-align:left;" valign="middle">2ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #cfe7f5 ; text-align:left;" valign="middle">3ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;" valign="middle">1ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;" valign="middle">2ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;" valign="middle">3ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" valign="middle">1ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" valign="middle">2ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" valign="middle">3ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ff9999 ; text-align:left;" valign="middle">1ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ff9999 ; text-align:left;" valign="middle">2ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ff9999 ; text-align:left;" valign="middle">3ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcc99 ; text-align:left;" valign="middle">1ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcc99 ; text-align:left;" valign="middle">2ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcc99 ; text-align:left;" valign="middle">3ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" valign="middle">1ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" valign="middle">2ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" valign="middle">3ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;" valign="middle">Relação</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" valign="middle">1ª</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;" valign="middle">2ª</td>
                        <td colspan="6" align="center" valign="middle">Última Amostra</td>
                        <td align="center" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;" valign="middle">Relação</td>
                        <td align="center" valign="middle">Sigla</td>
                    </tr>
                </thead>
            <tbody>  
              
            ');
                
        $td = 0;
        

        foreach ($tanques as $tanque){

        $this->documentBody .= ('
                
                    <tr>
                        <td>'.$tanque->sigla.'</td>
                        <td>'.$analises[$tanque->id]['dias_cultivo'].'</td>');
            //dd($analises[$tanque->id]['fosforos']);
            foreach ($analises[$tanque->id]['alcalinidades'] as $alcalindade){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffcc ; text-align:left;" >'.round($alcalindade->valor(), 2).'</td>');
                        $td += 1;
            }
            if($td < 3){
                for($i=$td;$i<3;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffcc;" >&nbsp;</td>'); 
                }
            }
            $td = 0;
            foreach ($analises[$tanque->id]['flocos'] as $floco){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #cfe7f5 ; text-align:left;">'.round($floco->valor(),2).'</td>');
                        $td += 1;
            }
            if($td < 3){
                for($i=$td;$i<3;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #cfe7f5 ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $td = 0;
            foreach ($analises[$tanque->id]['salinidades'] as $salinidade){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;">'.round($salinidade->valor(),2).'</td>');
                        $td += 1;
            }
            if($td < 3){
                for($i=$td;$i<3;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $td = 0;
            foreach ($analises[$tanque->id]['amonias'] as $amonia){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;">'.round($amonia->valor(),2).'</td>');
                        $td += 1;
            }
            if($td < 3){
                for($i=$td;$i<3;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $td = 0;
            foreach ($analises[$tanque->id]['nitritos'] as $nitrito){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ff9999 ; text-align:left;">'.round($nitrito->valor(),3).'</td>');
                        $td += 1;
            }
            if($td < 3){
                for($i=$td;$i<3;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ff9999 ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $td = 0;
            foreach ($analises[$tanque->id]['nitratos'] as $nitrato){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcc99 ; text-align:left;">'.round($nitrato->valor(),2).'</td>');
                        $td += 1;
            }
            if($td < 3){
                for($i=$td;$i<3;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcc99 ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $td = 0;
            foreach ($analises[$tanque->id]['fosforos'] as $fosforo){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;">'.round($fosforo->valor(),2).'</td>');
            $td += 1;
            }
            if($td < 3){
                for($i=$td;$i<3;$i++){
                        $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $td = 0;
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;">'.round($analises[$tanque->id]['np'],2).'</td>');
            
            foreach ($analises[$tanque->id]['silicas'] as $silica){
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;">'.round($silica->cpa_valor,2).'</td>');
            $td += 1;
            }
            if($td < 2){
                for($i=$td;$i<2;$i++){
                    $this->documentBody .= ('<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ccffcc ; text-align:left;">&nbsp;</td>'); 
                }
            }
            $td = 0;
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;">'.round($analises[$tanque->id]['dureza_total'],2).'</td>');
                        
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;">'.round($analises[$tanque->id]['dureza_calcio'],2).'</td>');

                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;">'.round($analises[$tanque->id]['dureza_magnesio'],2).'</td>');
                        
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;">'.round($analises[$tanque->id]['calcio'],2).'</td>');
                        
                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;">'.round($analises[$tanque->id]['magnesio']['cpa_valor'],2).'</td>');

                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffffff ; text-align:left;">'.round($analises[$tanque->id]['potassio']['cpa_valor'],2).'</td>');

                        $this->documentBody .= ('
                        <td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #ffcccc ; text-align:left;">'.$analises[$tanque->id]['ca_mg_k'].'</td>');
                        
                        $this->documentBody .= (
                        '<td>'.$tanque->sigla.'</td>');
            
                    $this->documentBody .= ('
                    </tr>');
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
