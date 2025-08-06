<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\VwEstoqueDisponivel;
use App\Models\TanquesTipos;

class CustosPorCicloReport extends mPDF
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
        
        $this->documentTitle = 'Relatório de custos por ciclo';

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
        $time1 = microtime(true);

        $ciclos           = $params[0];
        $data_solicitacao = $params[1];

        $data_exibicao    = $data_solicitacao->format('d/m/Y');
        $data_solicitacao = $data_solicitacao->format('Y-m-d');

        $itemPerPage = $ciclos->count();

        foreach ($ciclos as $ciclo) {

            $this->documentBody .= '<div class="content">';

            $this->documentBody .= '<table class="table_02">';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td colspan="3" style="border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Tanque: ' . $ciclo->tanque->sigla . ' (Nº' . $ciclo->numero . ')</td>';
            $this->documentBody .= '<td colspan="3" style="border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Data da solicitação: ' . $data_exibicao . '</td>';
            $this->documentBody .= '</tr>';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Tipo de cultivo</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Área do tanque</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Dias de preparação</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Dias de cultivo</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Dias de atividade</td>';
            $this->documentBody .= '<td style="width: 25%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Situação atual</td>';
            $this->documentBody .= '</tr>';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td>' . $ciclo->tipo() . '</td>';
            $this->documentBody .= '<td>' . $ciclo->tanque->hectares() . ' ha</td>';
            $this->documentBody .= '<td>' . $ciclo->dias_preparacao($data_solicitacao) . '</td>';
            $this->documentBody .= '<td>' . $ciclo->dias_cultivo($data_solicitacao) . '</td>';
            $this->documentBody .= '<td>' . $ciclo->dias_atividade($data_solicitacao) . '</td>';
            $this->documentBody .= '<td>' . ($ciclo->situacao() ?: 'n/a') . '</td>';
            $this->documentBody .= '</tr>';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Qtd. pós-larvas</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Sobrevivência</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Biomassa</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Peso médio</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">T.C.A.</td>';
            $this->documentBody .= '<td style="width: 25%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Densidade</td>';
            $this->documentBody .= '</tr>';

            $sobrevivencia = $ciclo->sobrevivencia($data_solicitacao);
            $biomassa      = $ciclo->biomassa($data_solicitacao);
            $peso_medio    = $ciclo->peso_medio($data_solicitacao);
            $tca           = $ciclo->tca($data_solicitacao);
            $densidade     = $ciclo->densidade($data_solicitacao);
            $qtd_poslarvas = 0;

            if (! is_null($ciclo->povoamento)) {
                $qtd_poslarvas = $ciclo->povoamento->qtd_poslarvas();
                $qtd_poslarvas = DataPolisher::numberFormat($qtd_poslarvas, 0);
            }

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td>' . (($qtd_poslarvas > 0) ? $qtd_poslarvas . ' UND' : 'n/a') . '</td>';
            $this->documentBody .= '<td>' . DataPolisher::numberFormat($sobrevivencia) . ' %</td>';
            $this->documentBody .= '<td>' . DataPolisher::numberFormat($biomassa) . ' Kg</td>';
            $this->documentBody .= '<td>' . DataPolisher::numberFormat($peso_medio) . ' g</td>';
            $this->documentBody .= '<td>' . DataPolisher::numberFormat($tca) . '</td>';
            $this->documentBody .= '<td>' . DataPolisher::numberFormat($densidade) . ' animais / m²</td>';
            $this->documentBody .= '</tr>';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Início do ciclo</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Início do povoamento</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Fim do povoamento</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Inicio da despesca</td>';
            $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Fim da despesca</td>';
            $this->documentBody .= '<td style="width: 25%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Dias de Despesca</td>';
            $this->documentBody .= '</tr>';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td>' . ($ciclo->data_inicio() ?: 'n/a') . '</td>';
            $this->documentBody .= '<td>' . (! is_null($ciclo->povoamento) ? $ciclo->povoamento->data_inicio() ?: 'n/a' : 'n/a') . '</td>';
            $this->documentBody .= '<td>' . (! is_null($ciclo->povoamento) ? $ciclo->povoamento->data_fim() ?: 'n/a' : 'n/a') . '</td>';
            $this->documentBody .= '<td>' . (! is_null($ciclo->despesca($data_solicitacao)) ? $ciclo->despesca($data_solicitacao)->data_inicio() ?: 'n/a' : 'n/a') . '</td>';
            $this->documentBody .= '<td>' . (! is_null($ciclo->despesca($data_solicitacao)) ? $ciclo->despesca($data_solicitacao)->data_fim() ?: 'n/a' : 'n/a') . '</td>';
            $this->documentBody .= '<td>' . (! is_null($ciclo->despesca($data_solicitacao)) ? $ciclo->despesca($data_solicitacao)->dias_despesca() ?: 'n/a' : 'n/a') . '</td>';
            $this->documentBody .= '</tr>';

            if (! is_null($ciclo->despesca($data_solicitacao))) {

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Fim do ciclo</td>';
                $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Qtd. prevista</td>';
                $this->documentBody .= '<td style="width: 15%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Qtd. despescada</td>';
                $this->documentBody .= '<td colspan="4" style="width: 55%; border-bottom: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Aproveitamento</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td>' . ($ciclo->data_fim() ?: 'n/a') .'</td>';
                $this->documentBody .= '<td>' . DataPolisher::numberFormat($ciclo->despesca($data_solicitacao)->qtd_prevista) . ' Kg</td>';
                $this->documentBody .= '<td>' . DataPolisher::numberFormat($ciclo->despesca($data_solicitacao)->qtd_despescada) . ' Kg</td>';
                $this->documentBody .= '<td>' . DataPolisher::numberFormat($ciclo->despesca($data_solicitacao)->aproveitamento()) . ' %</td>';
                $this->documentBody .= '</tr>';

            }

            $this->documentBody .= '</table>';

            $this->documentBody .= '<table class="table_02">';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td colspan="6" style="border-bottom: 1px solid #000; border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold; text-align: center;">Consumos próprios</td>';;
            $this->documentBody .= '</tr>';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td style="width: 37%; border-bottom: 1px solid #000; font-weight: bold;">Produto</td>';
            $this->documentBody .= '<td style="width: 21%; border-bottom: 1px solid #000; font-weight: bold;">Quantidade</td>';
            $this->documentBody .= '<td style="width: 21%; border-bottom: 1px solid #000; font-weight: bold;">Custo médio (estimado)</td>';
            $this->documentBody .= '<td style="width: 21%; border-bottom: 1px solid #000; font-weight: bold;">Custo total</td>';
            $this->documentBody .= '</tr>';

            $saidas = $ciclo->saidas_totais($data_solicitacao);

            foreach ($saidas as $saida) {

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="background-color: #e6e6e6;">' . $saida->produto_nome . '</td>';
                $this->documentBody .= '<td style="background-color: #e6e6e6;">' . DataPolisher::numberFormat($saida->quantidade) . ' ' . $saida->unidade_saida .'</td>';
                $this->documentBody .= '<td style="background-color: #e6e6e6;">R$ ' . DataPolisher::numberFormat($saida->valor_medio) . '</td>';
                $this->documentBody .= '<td style="background-color: #e6e6e6;">R$ ' . DataPolisher::numberFormat($saida->valor_total) . '</td>';
                $this->documentBody .= '</tr>';

            }
            if($ciclo->tanque->setor_id <> 8){ //se for tanqu de experimento contabiliza apenas as saidas

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="6" style="border-top: 1px solid #000; font-weight: bold; text-align: center;">Consumos recebidos de rateios</td>';
                $this->documentBody .= '</tr>';
            
            }

            $custo_rateio = 0;

            $consumos_recebidos = $ciclo->consumosRecebidos($ciclo->data_inicio, $data_solicitacao);

            foreach ($consumos_recebidos as $tanque_tipo_id => $produtos) {

                $tanque_tipo = TanquesTipos::find($tanque_tipo_id);

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="6" style="border-bottom: 1px solid #000; border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">' . $tanque_tipo->nome . '</td>';
                $this->documentBody .= '</tr>';

                $total_tipo = 0;

                foreach ($produtos as $produto_id => $quantidade) {

                    $produto = VwEstoqueDisponivel::where('filial_id', session('_filial')->id)
                    ->where('produto_id', $produto_id)
                    ->first();

                    $custo_consumo = $produto->valor_unitario * $quantidade;

                    $this->documentBody .= '<tr>';
                    $this->documentBody .= '<td style="background-color: #e6e6e6;">' . $produto->produto_nome . '</td>';
                    $this->documentBody .= '<td style="background-color: #e6e6e6;">' . DataPolisher::numberFormat($quantidade) . ' ' . $produto->unidade_saida->sigla .'</td>';
                    $this->documentBody .= '<td style="background-color: #e6e6e6;">R$ ' . DataPolisher::numberFormat($produto->valor_unitario) . '</td>';
                    $this->documentBody .= '<td style="background-color: #e6e6e6;">R$ ' . DataPolisher::numberFormat($custo_consumo) . '</td>';
                    $this->documentBody .= '</tr>';

                    $total_tipo += $custo_consumo;

                }

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; font-weight: bold;"></td>';
                $this->documentBody .= '<td colspan="1" style="border-top: 1px solid #000; font-weight: bold;">R$ ' . DataPolisher::numberFormat($total_tipo) . '</td>';
                $this->documentBody .= '</tr>';

                $custo_rateio += $total_tipo;

            }

            $custo_fixado = $ciclo->custo_fixado($data_solicitacao);
            $custo_saidas = $ciclo->custo_saidas($data_solicitacao);

            $custo_total = $custo_saidas + $custo_rateio + $custo_fixado->total;
            
            if($ciclo->tanque->setor_id == 8){ //se for tanqu de experimento contabiliza apenas as saidas

                $custo_total = $custo_saidas;

            }

            $custo_kg = ($biomassa > 0) ? ($custo_total / $biomassa) : 0;

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td colspan="6" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">TOTAIS</td>';
            $this->documentBody .= '</tr>';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Consumo próprio:</td>';
            $this->documentBody .= '<td colspan="1" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">R$ ' . DataPolisher::numberFormat($custo_saidas) . '</td>';
            $this->documentBody .= '</tr>';
                
            if($ciclo->tanque->setor_id <> 8){  //Não exibe esses dados no relatório quando foir tanque de experimento

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; font-weight: bold;">Consumo recebido de rateios:</td>';
                $this->documentBody .= '<td colspan="1" style="border-top: 1px solid #000; font-weight: bold;">R$ ' . DataPolisher::numberFormat($custo_rateio) . '</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; font-weight: bold;">Fixo:</td>';
                $this->documentBody .= '<td colspan="1" style="border-top: 1px solid #000; font-weight: bold;">R$ ' . DataPolisher::numberFormat($custo_fixado->fixo) . '</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; font-weight: bold;">Energia:</td>';
                $this->documentBody .= '<td colspan="1" style="border-top: 1px solid #000; font-weight: bold;">R$ ' . DataPolisher::numberFormat($custo_fixado->energia) . '</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; font-weight: bold;">Combustível:</td>';
                $this->documentBody .= '<td colspan="1" style="border-top: 1px solid #000; font-weight: bold;">R$ ' . DataPolisher::numberFormat($custo_fixado->combustivel) . '</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; font-weight: bold;">Depreciação:</td>';
                $this->documentBody .= '<td colspan="1" style="border-top: 1px solid #000; font-weight: bold;">R$ ' . DataPolisher::numberFormat($custo_fixado->depreciacao) . '</td>';
                $this->documentBody .= '</tr>';

            }

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Total geral:</td>';
            $this->documentBody .= '<td colspan="1" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">R$ ' . DataPolisher::numberFormat($custo_total) . '</td>';
            $this->documentBody .= '</tr>';

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Custo estimado por Kg:</td>';
            $this->documentBody .= '<td colspan="1" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">R$ ' . DataPolisher::numberFormat($custo_kg) . ' / Kg</td>';
            $this->documentBody .= '</tr>';

            $this->documentBody .= '</table>';

            $this->documentBody .= '</div>';

            $itemPerPage --;

            if ($itemPerPage > 0) {
                $this->documentBody .= '<pagebreak>';
            }

        }

        $time2 = microtime(true);

        // $this->documentBody .= '<div>' . round($time2 - $time1) . '</div>';

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
