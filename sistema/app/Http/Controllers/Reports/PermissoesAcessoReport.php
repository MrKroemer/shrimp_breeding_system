<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;
use App\Http\Controllers\Util\DataPolisher;

class PermissoesAcessoReport extends mPDF
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
        
        $this->documentTitle = 'Relatório de permissões de acesso';

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
                border: none;
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
                font: 2.6mm Georgia, serif;
                padding-left: 1mm;
            }
        ');

        $this->SetTitle($this->documentTitle);
        $this->setHTMLHeader($this->documentHeader);
        $this->setHTMLFooter($this->documentFooter);
    }

    public function MakeDocument(Array $params)
    {   
        $filiais = $params[0];

        $this->documentBody .= '<div class="content">';

        $this->documentBody .= '<table class="table_02">';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td colspan="4" style="border-bottom: 1px solid #000;">Emitido por: ' . auth()->user()->nome . '</td>';
        $this->documentBody .= '</tr>';

        $this->documentBody .= '<tr>';
        $this->documentBody .= '<td colspan="4" style="border-bottom: 1px solid #000;">&nbsp;</td>';
        $this->documentBody .= '</tr>';

        foreach ($filiais as $filial => $usuarios) {

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td colspan="1" style="border-bottom: 1px solid #000;">Filial:</td>';
            $this->documentBody .= '<td colspan="3" style="border-bottom: 1px solid #000; font-weight: bold;">' . $filial . '</td>';
            $this->documentBody .= '</tr>';

            foreach ($usuarios as $usuario => $grupos) {

                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="1" style="border-bottom: 1px solid #000;">Usuário:</td>';
                $this->documentBody .= '<td colspan="3" style="border-bottom: 1px solid #000; padding-left: 8mm; font-weight: bold;">' . $usuario . '</td>';
                $this->documentBody .= '</tr>';

                foreach ($grupos as $grupo => $modulos) {
                    
                    $this->documentBody .= '<tr>';
                    $this->documentBody .= '<td colspan="1" style="border-bottom: 1px solid #000;">Grupo:</td>';
                    $this->documentBody .= '<td colspan="3" style="border-bottom: 1px solid #000; padding-left: 16mm;">' . ($grupo ?: '[permissões definidas diretamente no usuário]') . '</td>';
                    $this->documentBody .= '</tr>';

                    foreach ($modulos as $modulo => $opcoes_menu) {

                        $this->documentBody .= '<tr>';
                        $this->documentBody .= '<td colspan="1" style="border-bottom: 1px solid #000;">Módulo:</td>';
                        $this->documentBody .= '<td colspan="3" style="border-bottom: 1px solid #000; padding-left: 24mm; font-weight: bold;">' . $modulo . '</td>';
                        $this->documentBody .= '</tr>';

                        foreach ($opcoes_menu as $opcao_menu => $acoes) {

                            $this->documentBody .= '<tr>';
                            $this->documentBody .= '<td colspan="1" style="border-bottom: 1px solid #000; background-color: #e6e6e6;">&nbsp;</td>';
                            $this->documentBody .= '<td colspan="1" style="border-bottom: 1px solid #000; background-color: #e6e6e6; padding-left: 32mm;">' . $opcao_menu . '</td>';
                            
                            $permissoes = '';

                            foreach ($acoes as $acao => $acesso) {

                                if ($acesso == 'Sim') {

                                    $permissoes .= $acao;

                                    if (next($acoes) == 'Sim') {
                                        $permissoes .= ', ';
                                    }

                                }

                            }

                            $this->documentBody .= '<td colspan="2" style="border-bottom: 1px solid #000; background-color: #e6e6e6;">' . ucwords($permissoes) . '</td>';
                            $this->documentBody .= '</tr>';

                        }

                    }

                }

            }

        }

        $this->documentBody .= '</table>';

        $this->documentBody .= '</div>';

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
