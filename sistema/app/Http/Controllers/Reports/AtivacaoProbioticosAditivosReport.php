<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;

class AtivacaoProbioticosAditivosReport extends mPDF
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
        
        $this->documentTitle = 'Relatório de ativação de probióticos e aditivos';

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
        $ativacoes       = $params[0];
        $receitas        = $params[1];
        $data_aplicacao  = $params[2];

        $colspan = 2;

        foreach ($ativacoes as $ativacao) {

            if (isset($ativacao['porcoes'])) {

                $num_porcoes = count($ativacao['porcoes']);
    
                if ($colspan < $num_porcoes) {
                    $colspan = $num_porcoes;
                }

            }

        }

        $this->documentBody .= ('
        <div class="content">
            <table class="table_01">
                <thead>
                    <tr>
                        <td colspan="' . $colspan . '" style="border-bottom: 1px solid #000;">Aplicações do dia: ' . $data_aplicacao . '</td>
                        <td colspan="' . ($colspan * 2) . '" style="text-align: center; border-bottom: 1px solid #000;">Aplicações de probióticos</td>
                        <td colspan="' . ($colspan * 2) . '" style="text-align: center; border-bottom: 1px solid #000;">Aplicações de aditivos</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                        <td colspan="' . $colspan . '" style="text-align: center; border-bottom: 1px solid #000;">Manhã</td>
                        <td colspan="' . $colspan . '" style="text-align: center; border-bottom: 1px solid #000;">Tarde</td>
                        <td colspan="' . $colspan . '" style="text-align: center; border-bottom: 1px solid #000;">Manhã</td>
                        <td colspan="' . $colspan . '" style="text-align: center; border-bottom: 1px solid #000;">Tarde</td>
                    </tr>
                    <tr>
                        <td>Tanques</td>
                        <td>Cultivo</td>
                        <td>Receita</td>
                        <td>Quantidade</td>
                        <td>Receita</td>
                        <td>Quantidade</td>
                        <td>Receita</td>
                        <td>Quantidade</td>
                        <td>Receita</td>
                        <td>Quantidade</td>
                    </tr>
                </thead>
                <tbody>
        ');

        $receitas_probioticos = [];
        $receitas_aditivos    = [];
        $totais_probioticos   = [];
        $totais_aditivos      = [];
        
        foreach ($ativacoes as $ativacao) {

            if (isset($ativacao['porcoes'])) {
            
                $this->documentBody .= '<tr>';

                $this->documentBody .= '<td>' . $ativacao['tanque_sigla'] . ' (Ciclo Nº ' .  $ativacao['ciclo_id'] . ')</td>';
                $this->documentBody .= '<td>' . $ativacao['dias_cultivo'] . ' Dias</td>';

                foreach ($ativacao['porcoes'] as $porcao) {

                    if (isset($porcao['probiotico_id'])) {

                        $this->documentBody .= '<td>' . (isset($porcao['probiotico_id']) ? $porcao['probiotico_nome'] : 'n/a') . '</td>';
                        $this->documentBody .= '<td>' . (isset($porcao['probiotico_id']) ? $porcao['probiotico_manha'] . ' ' . $porcao['probiotico_unidade'] : 'n/a') . '</td>';
                        $this->documentBody .= '<td>' . (isset($porcao['probiotico_id']) ? $porcao['probiotico_nome'] : 'n/a') . '</td>';
                        $this->documentBody .= '<td>' . (isset($porcao['probiotico_id']) ? $porcao['probiotico_tarde'] . ' ' . $porcao['probiotico_unidade'] : 'n/a') . '</td>';

                        $produtos = $receitas->find($porcao['probiotico_id'])->receitaPorDiasDeCultivo($ativacao['dias_cultivo'])->receita_laboratorial_produtos;

                        foreach ($produtos as $produto) {

                            if (! isset($receitas_probioticos[$porcao['probiotico_id']][$produto->produto_id])) {
                                $receitas_probioticos[$porcao['probiotico_id']][$produto->produto_id]['quantidade_manha'] = 0;
                                $receitas_probioticos[$porcao['probiotico_id']][$produto->produto_id]['quantidade_tarde'] = 0;
                            }

                            $receitas_probioticos[$porcao['probiotico_id']][$produto->produto_id]['nome']    = $produto->produto->nome;
                            $receitas_probioticos[$porcao['probiotico_id']][$produto->produto_id]['unidade'] = $produto->produto->unidade_saida->sigla;
                            $receitas_probioticos[$porcao['probiotico_id']][$produto->produto_id]['quantidade_manha'] += ($produto->quantidade * $porcao['probiotico_manha']);
                            $receitas_probioticos[$porcao['probiotico_id']][$produto->produto_id]['quantidade_tarde'] += ($produto->quantidade * $porcao['probiotico_tarde']);

                        }

                        if (! isset($totais_probioticos[$porcao['probiotico_id']])) {
                            $totais_probioticos[$porcao['probiotico_id']]['quantidade_manha'] = 0;
                            $totais_probioticos[$porcao['probiotico_id']]['quantidade_tarde'] = 0;
                        }

                        $totais_probioticos[$porcao['probiotico_id']]['quantidade_manha'] += $porcao['probiotico_manha'];
                        $totais_probioticos[$porcao['probiotico_id']]['quantidade_tarde'] += $porcao['probiotico_tarde'];

                    }

                    if (isset($porcao['aditivo_id'])) {

                        $this->documentBody .= '<td>' . (isset($porcao['aditivo_id']) ? $porcao['aditivo_nome'] : 'n/a') . '</td>';
                        $this->documentBody .= '<td>' . (isset($porcao['aditivo_id']) ? $porcao['aditivo_manha'] . ' ' . $porcao['aditivo_unidade'] : 'n/a') . '</td>';
                        $this->documentBody .= '<td>' . (isset($porcao['aditivo_id']) ? $porcao['aditivo_nome'] : 'n/a') . '</td>';
                        $this->documentBody .= '<td>' . (isset($porcao['aditivo_id']) ? $porcao['aditivo_tarde'] . ' ' . $porcao['aditivo_unidade'] : 'n/a') . '</td>';        

                        $produtos = $receitas->find($porcao['aditivo_id'])->receitaPorDiasDeCultivo($ativacao['dias_cultivo'])->receita_laboratorial_produtos;

                        foreach ($produtos as $produto) {

                            if (! isset($receitas_aditivos[$porcao['aditivo_id']][$produto->produto_id])) {
                                $receitas_aditivos[$porcao['aditivo_id']][$produto->produto_id]['quantidade_manha'] = 0;
                                $receitas_aditivos[$porcao['aditivo_id']][$produto->produto_id]['quantidade_tarde'] = 0;
                            }
                            
                            $receitas_aditivos[$porcao['aditivo_id']][$produto->produto_id]['nome']    = $produto->produto->nome;
                            $receitas_aditivos[$porcao['aditivo_id']][$produto->produto_id]['unidade'] = $produto->produto->unidade_saida->sigla;
                            $receitas_aditivos[$porcao['aditivo_id']][$produto->produto_id]['quantidade_manha'] += ($produto->quantidade * $porcao['aditivo_manha']);
                            $receitas_aditivos[$porcao['aditivo_id']][$produto->produto_id]['quantidade_tarde'] += ($produto->quantidade * $porcao['aditivo_tarde']);

                        }

                        if (! isset($totais_aditivos[$porcao['aditivo_id']])) {
                            $totais_aditivos[$porcao['aditivo_id']]['quantidade_manha'] = 0;
                            $totais_aditivos[$porcao['aditivo_id']]['quantidade_tarde'] = 0;
                        }
            
                        $totais_aditivos[$porcao['aditivo_id']]['quantidade_manha'] += $porcao['aditivo_manha'];
                        $totais_aditivos[$porcao['aditivo_id']]['quantidade_tarde'] += $porcao['aditivo_tarde'];

                    }

                }

                $this->documentBody .= '</tr>';
            
            }

        }

        $this->documentBody .= '</tbody></table>';

        $this->documentBody .= ('
        <table class="table_02">
            <tr>
                <td style="width: 26%;">
                    <table>
                        <tr>
                            <td colspan="3" style="text-align: center; border-bottom: 1px solid #000;">Totais por receitas e períodos</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; border-bottom: 1px solid #000;">Receita</td>
                            <td style="text-align: left; border-bottom: 1px solid #000;">Manhã</td>
                            <td style="text-align: left; border-bottom: 1px solid #000;">Tarde</td>
                        </tr>
        ');

        foreach ($totais_probioticos as $key => $probiotico) {

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td>' . $receitas->find($key)->nome . '</td>';
            $this->documentBody .= '<td>' . $probiotico['quantidade_manha'] . ' ' . $receitas->find($key)->unidade_medida->sigla .'</td>';
            $this->documentBody .= '<td>' . $probiotico['quantidade_tarde'] . ' ' . $receitas->find($key)->unidade_medida->sigla .'</td>';
            $this->documentBody .= '</tr>';

        }

        foreach ($totais_aditivos as $key => $aditivo) {

            $this->documentBody .= '<tr>';
            $this->documentBody .= '<td>' . $receitas->find($key)->nome . '</td>';
            $this->documentBody .= '<td>' . $aditivo['quantidade_manha'] . ' ' . $receitas->find($key)->unidade_medida->sigla .'</td>';
            $this->documentBody .= '<td>' . $aditivo['quantidade_tarde'] . ' ' . $receitas->find($key)->unidade_medida->sigla .'</td>';
            $this->documentBody .= '</tr>';

        }

        $this->documentBody .= ('
                    </table>
                </td>
                <td style="width: 37%;">
                    <table>
                        <tr>
                            <td colspan="3" style="text-align: center; border-bottom: 1px solid #000;">Insumos para receitas de probióticos</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Insumo</td>
                            <td style="text-align: left;">Manhã</td>
                            <td style="text-align: left;">Tarde</td>
                        </tr>
        ');

        foreach ($receitas_probioticos as $key => $receita) {

            $this->documentBody .= ('
                <tr>
                    <td colspan="3" style="text-align: center; border-bottom: 1px solid #000; border-top: 1px solid #000;">' . $receitas->find($key)->nome . '</td>
                </tr>
            ');

            foreach ($receita as $produto) {
                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td>' . $produto['nome'] . '</td>';
                $this->documentBody .= '<td>' . $produto['quantidade_manha'] . ' ' . $produto['unidade'] . '</td>';
                $this->documentBody .= '<td>' . $produto['quantidade_tarde'] . ' ' . $produto['unidade'] . '</td>';
                $this->documentBody .= '</tr>';
            }

        }
        
        $this->documentBody .= ('
                    </table>
                </td>
                <td style="width: 37%;">
                    <table>
                        <tr>
                            <td colspan="3" style="text-align: center; border-bottom: 1px solid #000;">Insumos para receitas de aditivos</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Insumo</td>
                            <td style="text-align: left;">Manhã</td>
                            <td style="text-align: left;">Tarde</td>
                        </tr>
        ');

        foreach ($receitas_aditivos as $key => $receita) {

            $this->documentBody .= ('
                <tr>
                    <td colspan="3" style="text-align: center; border-bottom: 1px solid #000; border-top: 1px solid #000;">' . $receitas->find($key)->nome . '</td>
                </tr>
            ');

            foreach ($receita as $produto) {
                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td>' . $produto['nome'] . '</td>';
                $this->documentBody .= '<td>' . $produto['quantidade_manha'] . ' ' . $produto['unidade'] . '</td>';
                $this->documentBody .= '<td>' . $produto['quantidade_tarde'] . ' ' . $produto['unidade'] . '</td>';
                $this->documentBody .= '</tr>';
            }

        }

        $this->documentBody .= ('
                    </table>
                </td>
            </tr>
        </table>
        </div>
        ');

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
