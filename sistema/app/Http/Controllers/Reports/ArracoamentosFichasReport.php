<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;
use App\Models\ArracoamentosHorarios;
use App\Models\ArracoamentosAplicacoes;

class ArracoamentosFichasReport extends mPDF
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
        
        $this->documentTitle = 'Programação de arraçoamentos';

        $this->documentHeader = '
            <table style="width: 100%;">
                <tr>
                    <td style="width: 30%; text-align: left;"><img style="width: 150px;" src="' . $this->documentLogo . '"/></td>
                    <td style="width: 40%; text-align: center; padding-top: 30px;"><h3 style="font-size: 4mm;">' . $this->documentTitle . '</h3></td>
                    <td style="width: 30%; text-align: right;"></td>
                </tr>
            </table>
            <div style="border-bottom: 1px solid #000; margin-top: 5px;"></div>
        ';

        $this->documentFooter = '
            <div style="width: 100%; border-bottom: 1px solid #000;"></div>
            <table style="width: 100%; font: italic 10px sans-serif;">
                <tr>
                    <td style="width: 50%; text-align: left;">Página: {PAGENO}/{nbpg}</td>
                    <td style="width: 50%; text-align: right;">Emitido em: {DATE d/m/Y H:i:s}</td>
                </tr>
            </table>
        ';

        $this->documentCss = '
            .content {
                border-top: 1px solid #000;
                border-left: 1px solid #000;
                border-right: 1px solid #000;
                margin-bottom: 4mm;
            }
            .table_body {
                width: 100%;
            }
            .table_01 {
                background-color: #e6e6e6;
                border-bottom: 1px solid #000;
            }
            .table_01 td {
                font: 10px Georgia, serif;
                padding-left: 1mm;
            }
            .table_02 {
                border: none;
            }
            .table_03 {
                font: bold 8px Georgia, serif;
                border: none;
            }
            .table_03 th {
                background-color: #b3b3b3;
            }
            .table_03 td {
                padding-left: 1mm;
            }
            .table_04 {
                font: 9px Georgia, serif;
                border: none;
                border-bottom: 1px solid #000;
            }
            .table_05 {
                background-color: #ffffff;
                font: 9px Georgia, serif;
            }

            .table_05 td {
                border-top: 1px solid #000; 
                border-left: 1px solid #000;
                height: 4.5mm;
            }
        ';

        $this->SetTitle($this->documentTitle);
        $this->setHTMLHeader($this->documentHeader);
        $this->setHTMLFooter($this->documentFooter);
    }

    public function MakeDocument(Array $params)
    {   
        $itemPerPage = 0;

        foreach ($params['arracoamentos'] as $arracoamento) {

                $this->documentBody .= '<div class="content">';
                $this->documentBody .= '<table class="table_body table_01">';
                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="width: 10%; text-align: left;">Tanque: ' . $arracoamento->tanque->nome . '</td>';
                $this->documentBody .= '<td style="width: 10%; text-align: left;">Ciclo Nº ' . $arracoamento->ciclo->numero . '</td>';
                $this->documentBody .= '<td style="width: 20%; text-align: left;">Aplicações do dia: ' . $arracoamento->data_aplicacao() . '</td>';
                $this->documentBody .= '<td style="width: 25%; text-align: left;">Emitido por: ' . auth()->user()->nome . '</td>';
                $this->documentBody .= '<td style="width: 35%; text-align: left;">Auxiliar:</td>';
                $this->documentBody .= '</tr>';
                $this->documentBody .= '</table>';

                $line_break = 1;

                $horarios = $arracoamento->horarios->sortBy('id');
                $num_horarios   = $horarios->count();

                $total_aplicacoes = $arracoamento->aplicacoes->count();

                $this->documentBody .= '<table cellpadding="0" cellspacing="0" class="table_body table_02">';

                $this->documentBody .= '<tr>';
    
                if ($total_aplicacoes > 0) {
        
                    while ($num_horarios < 10) {

                        $num_horarios ++;

                        $aux_arracoamento = new ArracoamentosHorarios;
                        $aux_arracoamento->ordinal = $num_horarios;

                        $horarios->push($aux_arracoamento);

                    }

                    foreach ($horarios as $horario) {

                        $this->documentBody .= '<td valign="top">';

                        $this->documentBody .= '<table cellpadding="1" cellspacing="1" class="table_body table_03">';

                        $this->documentBody .= '<tr>';
                        $this->documentBody .= '<th colspan="3" style="border-bottom: 1px solid #000;">' . $horario->ordinal . 'º horário</th>';
                        $this->documentBody .= '</tr>';

                        $this->documentBody .= '<tr>';
                        $this->documentBody .= '<th style="width: 50%;">Insumos</th>';
                        $this->documentBody .= '<th style="width: 30%;">Qtd.</th>';
                        $this->documentBody .= '<th style="width: 20%;">Corte</th>';
                        $this->documentBody .= '</tr>';

                        $aplicacoes = $horario->aplicacoes->sortBy('id');

                        $num_aplicacoes = $aplicacoes->count();

                        // dd($aplicacoes);

                        while ($num_aplicacoes < 2) {

                            $num_aplicacoes ++;
    
                            $aux_aplicacao = new ArracoamentosAplicacoes;
    
                            $aplicacoes->push($aux_aplicacao);
    
                        }

                        foreach ($aplicacoes as $aplicacao) {

                            $aplicacao_item = $aplicacao->arracoamento_aplicacao_item();

                            $item_sigla      = '&nbsp;';
                            $item_quantidade = '&nbsp;';
                            $item_unidade    = '&nbsp;';

                            if (! is_null($aplicacao_item->tipo)) {

                                $item_sigla      = substr($aplicacao_item->sigla, 0, 20);
                                $item_quantidade = round($aplicacao_item->quantidade, 1);
                                $item_unidade    = $aplicacao_item->unidade_medida;

                            }

                            $this->documentBody .= '<tr>';
                            $this->documentBody .= '<td style="width: 50%; border-bottom: 1px solid #000;">' . $item_sigla . '</td>';
                            $this->documentBody .= '<td style="width: 30%; border-bottom: 1px solid #000;">' . $item_quantidade . ' ' . $item_unidade . '</td>';
                            $this->documentBody .= '<td style="width: 20%; border: 1px solid #000;">&nbsp;</td>';
                            $this->documentBody .= '</tr>';

                        }

                        $this->documentBody .= '</table>';

                        $this->documentBody .= '</td>';

                        if (! ($line_break % 5) && $line_break < $num_horarios) {
                            $this->documentBody .= '</tr>';
                            $this->documentBody .= '<tr>';
                        }

                        $line_break ++;
                    }

                } else {

                    $this->documentBody .= '<td style="font: italic 11px arial; text-align: center; padding: 4mm 0 4mm 0;">';
                    $this->documentBody .= 'Não existem aplicações programadas para este tanque';
                    $this->documentBody .= '</td>';

                }

                $this->documentBody .= '</tr>';

                $this->documentBody .= '</table>';

                $this->documentBody .= '<table class="table_body table_04">';
                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="width: 25%; text-align: left; background-color: #e6e6e6;">Ração programada: <b>' . $arracoamento->qtdTotalRacaoProgramada() . ' KG</b></td>';
                $this->documentBody .= '<td style="width: 25%; text-align: left;">Ração consumida:</td>';
                $this->documentBody .= '<td style="width: 50%; text-align: left;">Período de muda: Sim ( )  Não ( )</td>';
                $this->documentBody .= '</tr>';
                $this->documentBody .= '</table>';

                $this->documentBody .= '<table cellpadding="1" cellspacing="0" class="table_body table_04 table_05">';
                
                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="2" style="width: 25%; text-align: center; border-top: none; border-left: none;background-color: #e6e6e6;"><b>Estoque de barraca</b></td>';
                $this->documentBody .= '<td colspan="2" style="width: 25%; text-align: center; border-top: none;background-color: #e6e6e6;"><b>Mortalidade</b></td>';
                $this->documentBody .= '<td colspan="1" style="width: 50%; text-align: center; border-top: none;background-color: #e6e6e6;"><b>Observações / Anotações:</b></td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="width: 12.5%; text-align: center; border-left: nonebackground-color: #e6e6e6;"><b>Ração</b></td>';
                $this->documentBody .= '<td style="width: 12.5%; text-align: center;background-color: #e6e6e6;"><b>Quantidade</b></td>';
                $this->documentBody .= '<td style="width: 12.5%; text-align: center;background-color: #e6e6e6;"><b>Local</b></td>';
                $this->documentBody .= '<td style="width: 12.5%; text-align: center;background-color: #e6e6e6;"><b>Quantidade</b></td>';
                $this->documentBody .= '<td style="width: 50%; text-align: left;"><i>'. $arracoamento->observacoes .'</i></td>';
                $this->documentBody .= '</tr>';
                        
                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="width: 12.5%; border-left: none;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 50%;">&nbsp;</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="width: 12.5%; border-left: none;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 50%;">&nbsp;</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="width: 12.5%; border-left: none;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 50%;">&nbsp;</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="width: 12.5%; border-left: none;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 50%;">&nbsp;</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td style="width: 12.5%; border-left: none;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 12.5%;">&nbsp;</td>';
                $this->documentBody .= '<td style="width: 50%;">&nbsp;</td>';
                $this->documentBody .= '</tr>';

                $this->documentBody .= '</table>';

                $this->documentBody .= '</div>';

                $itemPerPage ++;

                if ($itemPerPage == 3) {
                    
                    $this->documentBody .= '<pagebreak>';
                    
                    $itemPerPage = 0;

                }

        }

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
