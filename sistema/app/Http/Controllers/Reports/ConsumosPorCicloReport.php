<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;
use App\Http\Controllers\Util\DataPolisher;

class ConsumosPorCicloReport extends mPDF
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
        
        $this->documentTitle = 'Relatório de consumo por ciclos';

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
                padding-left: 1mm;
            }
            .table_02 {
                vertical-align: top;
            }
            .table_02 td {
                font: 2.5mm Georgia, serif;
                padding-left: 1mm;
            }
        ');

        $this->SetTitle($this->documentTitle);
        $this->setHTMLHeader($this->documentHeader);
        $this->setHTMLFooter($this->documentFooter);
    }

    public function MakeDocument(Array $params)
    {   
        $ciclos         = $params[0];
        $estoque_saidas = $params[1];
        $data_inicial   = $params[2];
        $data_final     = $params[3];

        foreach ($ciclos as $ciclo) {

            if ($estoque_saidas[$ciclo->id]->isNotEmpty()) {

                $this->documentBody .= '<div class="content">';

                $this->documentBody .= '<table class="table_02">';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="5" style="font-style: italic;">Consumos realizados no período de <b>' . $data_inicial . '</b> à <b>' . $data_final . '</b></td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="5" style="border-bottom: 1px solid #000; border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">' . $ciclo->tanque->sigla . ' (Nº' . $ciclo->numero . ')</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="width: 20%; border-bottom: 1px solid #000; font-weight: bold;">Produto</td>';
                $this->documentBody .= '<td style="width: 20%; border-bottom: 1px solid #000; font-weight: bold;">Quantidade</td>';
                $this->documentBody .= '<td style="width: 20%; border-bottom: 1px solid #000; font-weight: bold;">Valor unt. de saída</td>';
                $this->documentBody .= '<td style="width: 20%; border-bottom: 1px solid #000; font-weight: bold;">Custo estimado</td>';
                $this->documentBody .= '<td style="width: 20%; border-bottom: 1px solid #000; font-weight: bold;">Data do movimento</td>';
                $this->documentBody .= '</tr>';

                $totais_produtos = [];
                $custo_total = 0;

                foreach ($estoque_saidas[$ciclo->id] as $estoque_saida) {

                    $this->documentBody .= '<tr>';

                    $this->documentBody .= '<td>' . $estoque_saida->produto_nome . '</td>';
                    $this->documentBody .= '<td>' . DataPolisher::numberFormat($estoque_saida->quantidade) . ' ' . $estoque_saida->produto->unidade_saida->sigla . '</td>';
                    $this->documentBody .= '<td>R$ ' . DataPolisher::numberFormat($estoque_saida->valor_unitario) . '</td>';
                    $this->documentBody .= '<td>R$ ' . DataPolisher::numberFormat($estoque_saida->valor_total) . '</td>';
                    $this->documentBody .= '<td>' . $estoque_saida->data_movimento() . '</td>';

                    $this->documentBody .= '</tr>';

                    if (! isset($totais_produtos[$estoque_saida->produto_id])) {
                        $totais_produtos[$estoque_saida->produto_id] = [
                            'nome'        => $estoque_saida->produto_nome,
                            'unidade'     => $estoque_saida->produto->unidade_saida->sigla,
                            'quantidade'  => 0,
                            'valor_total' => 0,
                        ];
                    }

                    $totais_produtos[$estoque_saida->produto_id]['quantidade'] += $estoque_saida->quantidade;
                    $totais_produtos[$estoque_saida->produto_id]['valor_total'] += $estoque_saida->valor_total;

                    $custo_total += $estoque_saida->valor_total;

                }

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="5" style="border-bottom: 1px solid #000; border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">TOTAIS:</td>';
                $this->documentBody .= '</tr>';

                $totais_produtos = collect($totais_produtos)->sortBy('nome');

                foreach ($totais_produtos as $produto) {

                    $this->documentBody .= '<tr>';

                    $this->documentBody .= '<td>' . $produto['nome'] . '</td>';
                    $this->documentBody .= '<td>' . DataPolisher::numberFormat($produto['quantidade']) . ' ' . $produto['unidade'] . '</td>';
                    $this->documentBody .= '<td></td>';
                    $this->documentBody .= '<td>R$ ' . DataPolisher::numberFormat($produto['valor_total']) . '</td>';
                    $this->documentBody .= '<td></td>';

                    $this->documentBody .= '</tr>';

                }

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Custo estimado total:</td>';
                $this->documentBody .= '<td colspan="2" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">R$ ' . DataPolisher::numberFormat($custo_total) . '</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '</table>';

                $this->documentBody .= '</div>';

            }

        }

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
