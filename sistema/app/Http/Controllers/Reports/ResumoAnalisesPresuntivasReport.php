<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;

class ResumoAnalisesPresuntivasReport extends mPDF
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
        
        $this->documentTitle = 'Relatório de análises presuntivas';

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
                background-color: #fff;
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
                width: 2.32%;
            }
            .corpo {
                border-right: 1px solid #000;
                border-bottom: 1px solid #000;
            }
            .rodape {
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

        $data_exibicao    = $data_solicitacao->format('d/m/Y');
        $data_solicitacao = $data_solicitacao->format('Y-m-d');

        $this->documentBody .= '<div class="content">';

        $this->documentBody .= '<table class="table_01" cellpadding="0" cellspacing="0">';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td colspan="34" style="margin-left: 5px; border-bottom: 1px solid #000; text-align: left; background-color: #e6e6e6;">Data da Solicitação: ' . $data_exibicao . '</td>';
        $this->documentBody .= '</tr>';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td colspan="5" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #8cd070;">Opérculo</td>';
        // $this->documentBody .= '<td colspan="6" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Hepatopâncreas (Bacteriologia)</td>';
        $this->documentBody .= '<td colspan="10" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #8cd070;">Hepatopâncreas (Danos)</td>';
        $this->documentBody .= '<td colspan="9" style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f5aadb;">Intestino</td>';
        $this->documentBody .= '<td colspan="10" style="border-bottom: 1px solid #000; background-color: #8abfff;">Brânquias</td>';
        $this->documentBody .= '</tr>';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td rowspan="2" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">Viv.</td>';
        $this->documentBody .= '<td rowspan="2" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">Datas</td>';
        $this->documentBody .= '<td rowspan="2" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">1ª</td>';
        $this->documentBody .= '<td rowspan="2" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">2ª</td>';
        $this->documentBody .= '<td rowspan="2" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">3ª</td>';

        /* $this->documentBody .= '<td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">1ª</td>';
        $this->documentBody .= '<td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">2ª</td>'; */

        $this->documentBody .= '<td rowspan="2" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">1ª</td>';
        $this->documentBody .= '<td rowspan="2" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">2ª</td>';
        $this->documentBody .= '<td rowspan="2" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">3ª</td>';

        $this->documentBody .= '<td rowspan="2" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">Qld.</td>';

        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">1ª</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">2ª</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">3ª</td>';

        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">1ª</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">2ª</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">3ª</td>';

        $this->documentBody .= '<td colspan="3" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">1ª</td>';
        $this->documentBody .= '<td colspan="3" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">2ª</td>';
        $this->documentBody .= '<td colspan="3" style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">3ª</td>';

        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">1ª</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">2ª</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">3ª</td>';

        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">1ª</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">2ª</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">3ª</td>';

        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">1ª</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">2ª</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">3ª</td>';
        $this->documentBody .= '<td rowspan="2" style="width: 4%; border-bottom: 1px solid #000; background-color: #f3e184;">Adt</td>';
        $this->documentBody .= '</tr>';

        $this->documentBody .= '<tr>';
        // $this->documentBody .= '<td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>';

        /* $this->documentBody .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Sac +</td>';
        $this->documentBody .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Sac -</td>';
        $this->documentBody .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Sac ±</td>';
        $this->documentBody .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Sac +</td>';
        $this->documentBody .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Sac -</td>';
        $this->documentBody .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Sac ±</td>'; */

        // $this->documentBody .= '<td colspan="5" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">&nbsp;</td>';

        $this->documentBody .= '<td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Média Lipídeos</td>';
        
        $this->documentBody .= '<td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Coloração Associada</td>';

        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">Algas</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">Ração</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">M.O.</td>';

        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">Algas</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">Ração</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000;">M.O.</td>';

        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">Algas</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">Ração</td>';
        $this->documentBody .= '<td style="width: 4%; border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">M.O.</td>';

        $this->documentBody .= '<td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Necroses</td>';

        $this->documentBody .= '<td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Melanoses</td>';

        $this->documentBody .= '<td colspan="3" style="border-right: 1px solid #000; border-bottom: 1px solid #000;">Floco</td>';
        $this->documentBody .= '</tr>';

        $datasDasAnalises = [];
        $linhaDasDatas    = '';
        $linhasDosTanques = '';
        

        foreach ($ciclos as $ciclo) {

            $observacoes = [];
            $linhasDosTanques .= '<tr>';

            $analises_presuntivas = $ciclo->ciclo->analises_presuntivas
            ->where('data_analise', '<=', $data_solicitacao)
            ->sortByDesc('data_analise')
            ->take(3);

            if ($analises_presuntivas->isNotEmpty()) {

                $analises = $analises_presuntivas->sortBy('data_analise');
                
                $num_analises = $analises->count();
                $cont = 4 - $num_analises;
                

                $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">' . $ciclo->tanque_sigla . '</td>';
                
                
                
                $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;">';
                
                //ise
                foreach ($analises as $analise) {
                    
                    $linhasDosTanques .= $cont. 'º ' . $analise->data_analise('d/m') . '<br>';
                    $cont++;
                }

                $linhasDosTanques .='</td>';

                $j = 1;

                for ($i = 3; $i > $num_analises; $i --) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : '');
                    $linhasDosTanques .= '">&nbsp;</td>';

                    $j ++;

                }

                foreach ($analises as $analise) {

                    //Pega a ultima observação se existir
                    $observacoes[] = $analise->observacao; 

                    $datasDasAnalises[$analise->data_analise] = $analise->data_analise();

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;'; 
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : ''); 
                    $linhasDosTanques .= 'text-align:left;">';
                    
                    $linhasDosTanques .= 'G1-' . $analise->operculosGrau(1) . '%<br>';
                    $linhasDosTanques .= 'G2-' . $analise->operculosGrau(2) . '%<br>';
                    $linhasDosTanques .= 'G3-' . $analise->operculosGrau(3) . '%<br>';
                    $linhasDosTanques .= 'G4-' . $analise->operculosGrau(4) . '%';

                    $linhasDosTanques .='</td>';

                    $j ++;

                }

                $j = 1;

                for ($i = 3; $i > $num_analises; $i --) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : '');
                    $linhasDosTanques .= '">&nbsp;</td>';

                    $j ++;

                }

                $qualidade_lipideos = '&nbsp;';
                $media_lipideos     = '&nbsp;';

                foreach ($analises as $analise) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;'; 
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : ''); 
                    $linhasDosTanques .= 'text-align:left;">';

                    $linhasDosTanques .= 'G1-' . $analise->tubulosGrau(1) . '%<br>';
                    $linhasDosTanques .= 'G2-' . $analise->tubulosGrau(2) . '%<br>';
                    $linhasDosTanques .= 'G3-' . $analise->tubulosGrau(3) . '%<br>';
                    $linhasDosTanques .= 'G4-' . $analise->tubulosGrau(4) . '%';

                    $linhasDosTanques .='</td>';

                    $qualidade_lipideos =  $analise->qualidade_lipideos;
                    //$linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">' . $analise->lipideosMedia() . '</td>';

                    $j ++;

                }

                $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">' . $qualidade_lipideos . '</td>';
                //$linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; background-color: #f3e184;">' . $media_lipideos . '</td>';
                
                $j = 1;

                for ($i = 3; $i > $num_analises; $i --) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : '');
                    $linhasDosTanques .= '">&nbsp;</td>';

                    $j ++;

                }


                foreach ($analises as $analise) {
                    
                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000; ';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : ''); 
                    $linhasDosTanques .= 'text-align:center;">' . $analise->lipideosMedia() . '</td>';

                    $j ++;

                }

                $j = 1;

                for ($i = 3; $i > $num_analises; $i --) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : '');
                    $linhasDosTanques .= '">&nbsp;</td>';

                    $j ++;

                }

                foreach ($analises as $analise) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;'; 
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : ''); 
                    $linhasDosTanques .= '">';

                    $linhasDosTanques .= (! is_null($analise->total_coloracao_associada) ? $analise->total_coloracao_associada . '%' : '-');
                    $linhasDosTanques .= '</td>';

                    $j ++;

                }

                $j = 1;

                for ($i = 3; $i > $num_analises; $i --) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : '');
                    $linhasDosTanques .= '">&nbsp;</td>';

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : '');
                    $linhasDosTanques .= '">&nbsp;</td>';

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : '');
                    $linhasDosTanques .= '">&nbsp;</td>';

                    $j ++;

                }

                foreach ($analises as $analise) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;'; 
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : ''); 
                    $linhasDosTanques .= '">';

                    $linhasDosTanques .= (! is_null($analise->intestino_algas) ? $analise->intestino_algas . '%' : '-');
                    $linhasDosTanques .= '</td>';

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;'; 
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : ''); 
                    $linhasDosTanques .= '">';

                    $linhasDosTanques .= (! is_null($analise->intestino_racao) ? $analise->intestino_racao . '%' : '-');
                    $linhasDosTanques .= '</td>';

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;'; 
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : ''); 
                    $linhasDosTanques .= '">';

                    $linhasDosTanques .= (! is_null($analise->intestino_biofloco) ? $analise->intestino_biofloco . '%' : '-');
                    $linhasDosTanques .= '</td>';

                    $j ++;

                }

                $j = 1;

                for ($i = 3; $i > $num_analises; $i --) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : '');
                    $linhasDosTanques .= '">&nbsp;</td>';

                    $j ++;

                }

                foreach ($analises as $analise) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;'; 
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : ''); 
                    $linhasDosTanques .= '">'; 

                    $linhasDosTanques .= (! is_null($analise->necroses) ? $analise->necroses . '%' : '-');
                    $linhasDosTanques .= '</td>';

                    $j ++;

                }

                $j = 1;

                for ($i = 3; $i > $num_analises; $i --) {
                    
                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : '');
                    $linhasDosTanques .= '">&nbsp;</td>';

                    $j ++;

                }

                foreach ($analises as $analise) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;'; 
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : ''); 
                    $linhasDosTanques .= '">';

                    $linhasDosTanques .= (! is_null($analise->melanoses) ? $analise->melanoses . '%' : '-');
                    $linhasDosTanques .= '</td>';

                    $j ++;

                }

                $j = 1;

                for ($i = 3; $i > $num_analises; $i --) {
                    
                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;';
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : '');
                    $linhasDosTanques .= '">&nbsp;</td>';

                    $j ++;

                }

                $aditivo = '&nbsp;';

                foreach ($analises as $analise) {

                    $linhasDosTanques .= '<td style="border-right: 1px solid #000; border-bottom: 1px solid #000;'; 
                    $linhasDosTanques .= ($j >= 3 ? 'background-color: #f3e184;' : ''); 
                    $linhasDosTanques .= '">';

                    $linhasDosTanques .= (! is_null($analise->biofloco) ? $analise->biofloco . '%' : '-');
                    $linhasDosTanques .= '</td>';

                    $aditivo = $analise->aditivo;

                    $j ++;

                }

                $linhasDosTanques .= '<td style="border-bottom: 1px solid #000; background-color: #f3e184;">' . $aditivo . '</td>';

            }

            $linhasDosTanques .= '</tr>';

            

            foreach($observacoes as $key => $observacao){
                

                if($observacao){
                    $linhasDosTanques .= '<tr>';
                    $linhasDosTanques .= '<td colspan="32" style="margin-left: 5px; border-bottom: 1px solid #000; text-align: left; background-color: #e6e6e6;">Observações da amostra '.($key + 1).': ' . $observacao . '</td>';
                    $linhasDosTanques .= '</tr>';
                }
            }            
            
        }

         

        $this->documentBody .= $linhasDosTanques;

        $this->documentBody .= '</table>';

        $this->documentBody .= '</div>';
        //dd($this->documentBody);
        $time2 = microtime(true);

        // $this->documentBody .= '<div>' . round($time2 - $time1) . '</div>';

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
