<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;

class AnalisesAguaReport extends mPDF
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

        $this->documentHeader = 
            '<table style="width: 100%;">
                <tr>
                    <td style="width: 20%; text-align: left;"><img style="width: 150px;" src="' . $this->documentLogo . '"/></td>
                    <td style="width: 60%; text-align: center; padding-top: 30px;"><h3 style="font-size: 4mm;">' . $this->documentTitle . '</h3></td>
                    <td style="width: 20%; text-align: right;"></td>
                </tr>
            </table>
            <div style="border-bottom: 1px solid #000; margin-top: 5px;"></div>';

        $this->documentFooter = 
            '<div style="width: 100%; border-bottom: 1px solid #000;"></div>
            <table style="width: 100%; font: italic 10px sans-serif;">
                <tr>
                    <td style="width: 50%; text-align: left;">Página: {PAGENO}/{nbpg}</td>
                    <td style="width: 50%; text-align: right;">Emitido em: {DATE d/m/Y H:i:s}</td>
                </tr>
            </table>';

        $this->documentCss = 
            '.content {
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
                padding-left: 1mm;
            }
            .table_02 {
                vertical-align: top;
            }
            .table_02 td {
                font: 2.5mm Georgia, serif;
                padding-left: 1mm;
            }';

        $this->SetTitle($this->documentTitle);
        $this->setHTMLHeader($this->documentHeader);
        $this->setHTMLFooter($this->documentFooter);
    }

    public function MakeDocument(Array $params)
    {   
        $tanques = $params[0];
        $analises= $params[1];
        $data_aplicacao = $params[2];          

        $this->documentBody .= 
        '<div class="content">
            <table class="table_01">
                <tr>
                    <td style="width:60%;">Data de Referência: ' . $data_aplicacao . '</td>
                    <td style="width:40%;">Emitido por: ' . auth()->user()->nome . '</td>
                </tr>
            </table>
            <table class="table_02">
                <thead>
                    <tr>
                        <td align="center" valign="middle"><strong>Viveiro</strong></td>
                        <td align="center" valign="middle"><strong>Cultivo</strong></td>
                        <td colspan="6" align="center" valign="middle"><strong>Bacteriologia da Água</strong></td>
                        <td colspan="3" align="center" valign="middle"><strong>Floco</strong></td>
                        <td colspan="3" align="center" valign="middle"><strong>Salinidade</strong></td>
                        <td colspan="3" align="center" valign="middle"><strong>Alcalinidade</strong></td>
                        <td colspan="3" align="center" valign="middle"><strong>NH3</strong></td>
                        <td colspan="3" align="center" valign="middle"><strong>NO2</strong></td>
                        <td colspan="3" align="center" valign="middle"><strong>NO3</strong></td>
                        <td colspan="3" align="center" valign="middle"><strong>PO4</strong></td>
                        <td align="center" valign="middle"><strong>N:P</strong></td>
                        <td colspan="2" align="center" valign="middle"><strong>Sílica</strong></td>
                        <td align="center" valign="middle"><strong>Dr CA</strong></td>
                        <td align="center" valign="middle"><strong>Ca</strong></td>
                        <td align="center" valign="middle"><strong>Mg</strong></td>
                        <td align="center" valign="middle"><strong>K</strong></td>
                        <td align="center" valign="middle"><strong>Ca:Mg:K</strong></td>
                        <td align="center" valign="middle"><strong>Viveiro</strong></td>
                    </tr>
                    <tr>
                        <td rowspan="2" align="center" valign="middle">Siglas</td>
                        <td rowspan="2" align="center" valign="middle">Dias</td>
                        <td colspan="3" align="center" valign="middle">1ª</td>
                        <td colspan="3" align="center" valign="middle">2ª</td>
                        <td rowspan="2" align="center" valign="middle">1ª</td>
                        <td rowspan="2" align="center" valign="middle">2ª</td>
                        <td rowspan="2" align="center" valign="middle">3ª</td>
                        <td rowspan="2" align="center" valign="middle">1ª</td>
                        <td rowspan="2" align="center" valign="middle">2ª</td>
                        <td rowspan="2" align="center" valign="middle">3ª</td>
                        <td rowspan="2" align="center" valign="middle">1ª</td>
                        <td rowspan="2" align="center" valign="middle">2ª</td>
                        <td rowspan="2" align="center" valign="middle">3ª</td>
                        <td rowspan="2" align="center" valign="middle">1ª</td>
                        <td rowspan="2" align="center" valign="middle">2ª</td>
                        <td rowspan="2" align="center" valign="middle">3ª</td>
                        <td rowspan="2" align="center" valign="middle">1ª</td>
                        <td rowspan="2" align="center" valign="middle">2ª</td>
                        <td rowspan="2" align="center" valign="middle">3ª</td>
                        <td rowspan="2" align="center" valign="middle">1ª</td>
                        <td rowspan="2" align="center" valign="middle">2ª</td>
                        <td rowspan="2" align="center" valign="middle">3ª</td>
                        <td rowspan="2" align="center" valign="middle">1ª</td>
                        <td rowspan="2" align="center" valign="middle">2ª</td>
                        <td rowspan="2" align="center" valign="middle">3ª</td>
                        <td rowspan="2" align="center" valign="middle">Relação</td>
                        <td rowspan="2" align="center" valign="middle">1ª</td>
                        <td rowspan="2" align="center" valign="middle">2ª</td>
                        <td colspan="4" rowspan="2" align="center" valign="middle">Última Amostra</td>
                        <td rowspan="2" align="center" valign="middle">Relação</td>
                        <td rowspan="2" align="center" valign="middle">Sigla</td>
                    </tr>
                    <tr>
                        <td align="center" valign="middle">Sac+</td>
                        <td align="center" valign="middle">Sac-</td>
                        <td align="center" valign="middle">Sac±</td>
                        <td align="center" valign="middle">Sac+</td>
                        <td align="center" valign="middle">Sac-</td>
                        <td align="center" valign="middle">Sac±</td>
                    </tr>';

                    foreach ($tanques as $tanque){
                        $this->documentBody .= '<tr>
                        <td>{tanque->sigla}</td>
                        <td>dias</td>';
                        foreach ($analises[$tanque->id]->bacteriologias as $bacteriologia){
                            $this->documentBody .= '<td>{$bacteriologia->positivas}</td>
                            <td>{$bacteriologia->negativas}</td>
                            <td>{$bacteriologia->variaveis}</td>';
                        }
                        foreach ($analises[$tanque->id]->flocos as $floco){
                            $this->documentBody .= '<td>{$floco->cpa_valor}</td>';
                        }
                        foreach ($analises[$tanque->id]->salinidades as $salinidade){
                            $this->documentBody .= '<td>{$salinidade->cpa_valor}</td>';
                        }
                        foreach ($analises[$tanque->id]->salinidades as $salinidade){
                            $this->documentBody .= '<td>{$salinidade->cpa_valor}</td>';
                        } 
                            '<td>1alcalinidade</td>
                            <td>2alcalinidade</td>
                            <td>3alcalinidade</td>
                            <td>1nh3</td>
                            <td>2nh3</td>
                            <td>3nh3</td>
                            <td>1no2</td>
                            <td>2no2</td>
                            <td>3no2</td>
                            <td>1no3</td>
                            <td>2no3</td>
                            <td>3no3</td>
                            <td>1PO4</td>
                            <td>2PO4</td>
                            <td>3PO4</td>
                            <td>N:P</td>
                            <td>1Sílica</td>
                            <td>2Sílica</td>
                            <td>Dr CA</td>
                            <td>Ca</td>
                            <td>Mg</td>
                            <td>K</td>
                            <td>Ca:Mg:K</td>
                            <td>Viveiro</td>
                            </tr>';
                    }

                $this->documentBody .= '</thead>
                <tbody>';

        foreach ($aplicacoes as $aplicacao) {

            $aplicacao['solvente'] = ! is_null($aplicacao['solvente']) ? $aplicacao['solvente'] : 0;

            $this->documentBody .= '<tr>';

            $this->documentBody .= '<td style="width:20%; border-bottom: 1px solid #000;">' . $aplicacao['tanque'] . '</td>';
            $this->documentBody .= '<td style="width:20%; border-bottom: 1px solid #000;">' . (float) $aplicacao['quantidade'] . '</td>';
            $this->documentBody .= '<td style="width:20%; border-bottom: 1px solid #000;">' . ($aplicacao['solvente'] > 0 ? (float) $aplicacao['solvente'] : 'Qtd. não definida') . '</td>';
            $this->documentBody .= '<td style="width:40%; border-bottom: 1px solid #000;">' . (float) ($aplicacao['quantidade'] + $aplicacao['solvente']) . '</td>';

            $this->documentBody .= '</tr>';

            $total_soluto += $aplicacao['quantidade'];
            $total_solvente += $aplicacao['solvente'];

        }

        $this->documentBody .= '<tr style="background-color: #e6e6e6;">';
        $this->documentBody .= '<td style="width:20%;">Totais:</td>';
        $this->documentBody .= '<td style="width:20%;">' . (float) $total_soluto . '</td>';
        $this->documentBody .= '<td style="width:20%;">' . (float) $total_solvente . '</td>';
        $this->documentBody .= '<td style="width:40%;">' . (float) ($total_soluto + $total_solvente) . '</td>';
        $this->documentBody .= '</tr>';

        $this->documentBody .= 
                '</tbody>
            </table>
        </div>';

        $this->documentBody .= '<pagebreak>'; // Adiciona uma nova página ao relatório

        if (count($ativacoes) > 0) {

            $this->documentBody .= 
            '<div class="content">
                <table class="table_01">
                    <tr>
                        <td style="width:30%;">Ativação das aplicações do dia: ' . $data_ativacao . '</td>
                        <td style="width:40%;">Receita: ' . $receita->nome . '</td>
                        <td style="width:30%;">Emitido por: ' . auth()->user()->nome . '</td>
                    </tr>
                </table>
                <table class="table_01">';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td style="width:40%; border-bottom: 1px solid #000;">Produtos / Insumos</td>';
            $this->documentBody .= '<td style="width:60%; border-bottom: 1px solid #000;">Quantidade para ativação</td>';
            $this->documentBody .= '</tr>';

            $total_soluto = 0;
            $total_solvente = 0;

            foreach ($ativacoes as $ativacao) {

                $ativacao['solvente'] = ! is_null($ativacao['solvente']) ? $ativacao['solvente'] : 0;

                $total_soluto += $ativacao['quantidade'];
                $total_solvente += $ativacao['solvente'];

            }

            if ($receita->receita_laboratorial_periodos->count() == 1) {

                foreach ($receita->receita_laboratorial_produtos as $receita_produto) {

                    $this->documentBody .= '<tr>';
                    $this->documentBody .= '<td style="width:40%;">' . $receita_produto->produto->nome . '</td>';
                    $this->documentBody .= '<td style="width:60%;">' . (float) ($receita_produto->quantidade * $total_soluto) . ' ' . $receita_produto->produto->unidade_saida->sigla . '</td>';
                    $this->documentBody .= '</tr>';

                }

            }

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td style="width:20%; border-top: 1px solid #000;">Total de solução:</td>';
            $this->documentBody .= '<td style="width:40%; border-top: 1px solid #000;">' . (float) ($total_soluto + $total_solvente) . ' ' . $receita->unidade_medida->sigla . '</td>';
            $this->documentBody .= '</tr>';

            $this->documentBody .= 
                '</table>
            </div>';

        }

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
