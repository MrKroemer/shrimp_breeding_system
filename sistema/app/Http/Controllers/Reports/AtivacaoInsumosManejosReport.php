<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;

class AtivacaoInsumosManejosReport extends mPDF
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
            'orientation'   => 'P', 
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
        
        $this->documentTitle = 'Relatório de aplicações e ativações de receitas';

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
        $receita        = $params[0];
        $aplicacoes     = $params[1];
        $data_aplicacao = $params[2];
        $ativacoes      = $params[3];
        $data_ativacao  = $params[4];

        $this->documentBody .= '<div class="content">';

        $this->documentBody .= '<table class="table_01">';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td style="width:30%;">Aplicações do dia: ' . $data_aplicacao . '</td>';
        $this->documentBody .= '<td style="width:40%;">Receita: ' . $receita->nome . '</td>';
        $this->documentBody .= '<td style="width:30%;">Emitido por: ' . auth()->user()->nome . '</td>';
        $this->documentBody .= '</tr>';

        $this->documentBody .= '</table>';

        $this->documentBody .= '<table class="table_02">';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td style="width:40%; border-bottom: 1px solid #000;">Tanque (Ciclo) (DDC)</td>';
        // $this->documentBody .= '<td style="width:20%; border-bottom: 1px solid #000;">Soluto (Receita) (' . $receita->unidade_medida->sigla . ')</td>';
        // $this->documentBody .= '<td style="width:20%; border-bottom: 1px solid #000;">Solvente (Água) (' . $receita->unidade_medida->sigla . ')</td>';
        $this->documentBody .= '<td style="width:60%; border-bottom: 1px solid #000;">Solução à aplicar (' . $receita->unidade_medida->sigla . ')</td>';
        $this->documentBody .= '</tr>';

        $total_soluto = 0;
        $total_solvente = 0;

        foreach ($aplicacoes->sortBy('sigla') as $aplicacao) {

            $aplicacao['solvente'] = ! is_null($aplicacao['solvente']) ? $aplicacao['solvente'] : 0;
            $aplicacao['ciclo']    = $aplicacao['tanque']->ultimo_ciclo();

            $this->documentBody .= '<tr>';

            $this->documentBody .= '<td style="width:40%; border-bottom: 1px solid #000;">' . $aplicacao['tanque']->sigla . ' (' . (! is_null($aplicacao['ciclo']) ? $aplicacao['ciclo']->numero . ') (' . $aplicacao['ciclo']->dias_cultivo() : 'n/a') .')</td>';
            // $this->documentBody .= '<td style="width:20%; border-bottom: 1px solid #000;">' . (float) $aplicacao['quantidade'] . '</td>';
            // $this->documentBody .= '<td style="width:20%; border-bottom: 1px solid #000;">' . ($aplicacao['solvente'] > 0 ? (float) $aplicacao['solvente'] : 'Qtd. não definida') . '</td>';
            $this->documentBody .= '<td style="width:60%; border-bottom: 1px solid #000;">' . (float) ($aplicacao['quantidade'] + $aplicacao['solvente']) . '</td>';

            $this->documentBody .= '</tr>';

            $total_soluto += $aplicacao['quantidade'];
            $total_solvente += $aplicacao['solvente'];

        }

        $this->documentBody .= '<tr style="background-color: #e6e6e6;">';
        $this->documentBody .= '<td style="width:40%;">Totais:</td>';
        // $this->documentBody .= '<td style="width:20%;">' . (float) $total_soluto . '</td>';
        // $this->documentBody .= '<td style="width:20%;">' . (float) $total_solvente . '</td>';
        $this->documentBody .= '<td style="width:60%;">' . (float) ($total_soluto + $total_solvente) . '</td>';
        $this->documentBody .= '</tr>';

        $this->documentBody .= '</table>';

        $this->documentBody .= '</div>';

        $this->documentBody .= '<pagebreak>'; // Adiciona uma nova página ao relatório

        if (count($ativacoes) > 0) {

            $this->documentBody .= '<div class="content">';

            $this->documentBody .= '<table class="table_01">';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td style="width:30%;">Ativação das aplicações do dia: ' . $data_ativacao . '</td>';
            $this->documentBody .= '<td style="width:40%;">Receita: ' . $receita->nome . '</td>';
            $this->documentBody .= '<td style="width:30%;">Emitido por: ' . auth()->user()->nome . '</td>';
            $this->documentBody .= '</tr>';

            $this->documentBody .= '</table>';

            $this->documentBody .= '<table class="table_01">';

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

            $this->documentBody .= '</table>';

            $this->documentBody .= '</div>';

        }

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
