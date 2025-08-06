<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;
use App\Http\Controllers\Util\DataPolisher;

class ConsumosPorProdutoSimplificadoReport extends mPDF
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
        
        $this->documentTitle = 'Relatório de consumo por produtos';

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
        $estoque_saidas = $params[0];
        $data_inicial   = $params[1];
        $data_final     = $params[2];

        $this->documentBody .= '<div class="content">';

        $this->documentBody .= '<table class="table_02">';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td colspan="3" style="border-bottom: 1px solid #000;"><i>Consumos realizados no período de <b>' . $data_inicial . '</b> à <b>' . $data_final . '</b></i></td>';
        $this->documentBody .= '</tr>';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td style="width: 33%; border-bottom: 1px solid #000; background-color: #e6e6e6;"><b>Produto</b></td>';
        $this->documentBody .= '<td style="width: 33%; border-bottom: 1px solid #000; background-color: #e6e6e6;"><b>Quantidade</b></td>';
        $this->documentBody .= '<td style="width: 33%; border-bottom: 1px solid #000; background-color: #e6e6e6;"><b>Custo estimado</b></td>';
        $this->documentBody .= '</tr>';

        foreach ($estoque_saidas as $estoque_saida) {

            $qtd_total   = 0;
            $custo_total = 0;

            $consumos = collect($estoque_saida['consumos'])->sortBy('date');

            foreach ($consumos as $consumo) {
                $qtd_total   += $consumo['saida']->quantidade;
                $custo_total += $consumo['saida']->valor_total;
            }

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td style="border-bottom: 1px solid #000;">' . $consumo['saida']->produto->nome . '</td>';
            $this->documentBody .= '<td style="border-bottom: 1px solid #000;">' . DataPolisher::numberFormat($qtd_total, 4) . ' ' . $consumo['saida']->produto->unidade_saida->sigla . '</td>';
            $this->documentBody .= '<td style="border-bottom: 1px solid #000;">R$ ' . DataPolisher::numberFormat($custo_total) . '</td>';
            $this->documentBody .= '</tr>';

        }

        $this->documentBody .= '</table>';

        $this->documentBody .= '</div>';

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
