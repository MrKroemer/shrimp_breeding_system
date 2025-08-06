<?php

namespace App\Http\Controllers\Reports;

use Mpdf\Mpdf as mPDF;
use Mpdf\HTMLParserMode;
use Carbon\Carbon;
use App\Models\AplicacoesInsumosGruposObservacoes;

class AplicacoesInsumosFichasReport extends mPDF
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
        
        $this->documentTitle = 'Ficha de aplicações de insumos';

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
                font: 2.7mm Georgia, serif;
            }
            .table_01 {
                background-color: #e6e6e6;
            }
            .table_01 td {
                padding: 0.1mm auto 0.1mm 1mm;
                background-color: #fff;
            }
            .no_border_bottom {
                border-bottom: nome;
            }
            .table_02 td {
                border-bottom: 0.2mm solid #000;
            }
            .header_div { 
                border: 1px solid #000;
                padding: 0.5mm auto 0.5mm 1mm; 
                margin: 0 0 2mm 0;
                background-color: #e6e6e6;
                font: 2.7mm Georgia, serif;
            }
        ');

        $this->SetTitle($this->documentTitle);
        $this->setHTMLHeader($this->documentHeader);
        $this->setHTMLFooter($this->documentFooter);
    }

    public function MakeDocument(Array $params)
    {
        $aplicacoes_insumos      = $params[0];
        $aplicacao_insumo_grupo  = $params[1];
        $data_aplicacao          = $params[2];
        $receita_laboratorial_id = $params[3];

        $receitas = [];
        $produtos = [];

        $this->documentBody .= ('
        <div class="row header_div">
            [Aplicações do dia: ' . $data_aplicacao . '] ' . (! empty($aplicacao_insumo_grupo) ? '[' . $aplicacao_insumo_grupo->nome . ']' : '') . '
        </div>
        <div class="content">
            <table cellpadding="0" cellspacing="0" class="table_01">
        ');

        foreach ($aplicacoes_insumos as $aplicacao_insumo) {

            $aplicacoes_receitas = $aplicacao_insumo->aplicacoes_insumos_receitas;
            $aplicacoes_produtos = $aplicacao_insumo->aplicacoes_insumos_produtos;

            if (! empty($receita_laboratorial_id)) {
                $aplicacoes_receitas = $aplicacao_insumo->aplicacoes_insumos_receitas->where('receita_laboratorial_id', $receita_laboratorial_id);
            }

            if ($aplicacoes_receitas->isNotEmpty() || $aplicacoes_produtos->isNotEmpty()) {

                $ciclo = $aplicacao_insumo->tanque->ultimo_ciclo();

                $this->documentBody .= ('
                    <tr>
                        <td colspan="3" style="background-color: #b3b3b3; font-weight: bold;">' . $aplicacao_insumo->tanque->sigla . ' (' . (! is_null($ciclo) ? 'Ciclo: ' . $ciclo->numero . ') (DDC: ' . $ciclo->dias_cultivo() : 'n/a') . ')</td>
                    </tr>
                    <tr>
                        <td colspan="3"><b>Observações:</b><i> ' . $aplicacao_insumo->observacoes . '</i></td>
                    </tr>
                    <tr>
                        <td colspan="1" style="background-color: #e6e6e6; font-weight: bold;">Produtos / Insumos</td>
                        <td colspan="2" style="background-color: #e6e6e6; font-weight: bold;">Quantidade</td>
                    </tr>
                ');

                foreach ($aplicacoes_receitas as $aplicacao_receita) {

                    $qtd_receita = ((float) $aplicacao_receita->quantidade);

                    $this->documentBody .= ('
                        <tr>
                            <td colspan="1" style="border-top: 1px solid #000;">' . $aplicacao_receita->receita_laboratorial->nome . '</td>
                            <td colspan="2" style="border-top: 1px solid #000;">' . $qtd_receita . ' ' . $aplicacao_receita->receita_laboratorial->unidade_medida->sigla . '</td>
                        </tr>
                    ');

                    if (! isset($receitas[$aplicacao_receita->receita_laboratorial_id])) {
                        $receitas[$aplicacao_receita->receita_laboratorial_id]['nome'] = $aplicacao_receita->receita_laboratorial->nome;
                        $receitas[$aplicacao_receita->receita_laboratorial_id]['unidade'] = $aplicacao_receita->receita_laboratorial->unidade_medida->sigla;
                        $receitas[$aplicacao_receita->receita_laboratorial_id]['quantidade'] = 0;
                    }

                    $receitas[$aplicacao_receita->receita_laboratorial_id]['quantidade'] += $qtd_receita;

                }

                foreach ($aplicacoes_produtos as $aplicacao_produto) {

                    $qtd_produto = ((float) $aplicacao_produto->quantidade);

                    $this->documentBody .= ('
                        <tr>
                            <td colspan="1" style="border-top: 1px solid #000;">' . $aplicacao_produto->produto->nome . '</td>
                            <td colspan="2" style="border-top: 1px solid #000;">' . $qtd_produto . ' ' . $aplicacao_produto->produto->unidade_saida->sigla . '</td>
                        </tr>
                    ');

                    if (! isset($produtos[$aplicacao_produto->produto_id])) {
                        $produtos[$aplicacao_produto->produto_id]['nome'] = $aplicacao_produto->produto->nome;
                        $produtos[$aplicacao_produto->produto_id]['unidade'] = $aplicacao_produto->produto->unidade_saida->sigla;
                        $produtos[$aplicacao_produto->produto_id]['quantidade'] = 0;
                    }

                    $produtos[$aplicacao_produto->produto_id]['quantidade'] += $qtd_produto;

                }
                
            }
        }

        $this->documentBody .= ('
            </table>
        </div>
        <div class="content">
            <table cellpadding="0" cellspacing="0" class="table_01">
                <tr>
                    <td colspan="3" style="background-color: #b3b3b3; font-weight: bold;">Totais por insumo</td>
                </tr>
                <tr>
                    <td colspan="1" style="background-color: #e6e6e6; font-weight: bold;">Produtos / Insumos</td>
                    <td colspan="2" style="background-color: #e6e6e6; font-weight: bold;">Quantidade</td>
                </tr>
        ');

        foreach ($receitas as $receita) {
            $this->documentBody .= ('
                <tr>
                    <td colspan="1" style="border-top: 1px solid #000;">' . $receita['nome'] . '</td>
                    <td colspan="2" style="border-top: 1px solid #000;">' . $receita['quantidade'] . ' ' . $receita['unidade'] . '</td>
                </tr>
            ');
        }

        foreach ($produtos as $produto) {
            $this->documentBody .= ('
                <tr>
                    <td colspan="1" style="border-top: 1px solid #000;">' . $produto['nome'] . '</td>
                    <td colspan="2" style="border-top: 1px solid #000;">' . $produto['quantidade'] . ' ' . $produto['unidade'] . '</td>
                </tr>
            ');
        }

        $this->documentBody .= ('
            </table>
        </div>
        ');

        $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data_aplicacao)->format('Y-m-d');

        $observacoes_gerais = AplicacoesInsumosGruposObservacoes::where('data_aplicacao', $data_aplicacao);

        if (! empty($aplicacao_insumo_grupo)) {
            $observacoes_gerais->where('aplicacao_insumo_grupo_id', $aplicacao_insumo_grupo->id);
        }

        $this->documentBody .= ('
        <div class="content no_border_bottom">
            <table cellpadding="0" cellspacing="0" class="table_01 table_02">
                <tr><td colspan="3" style="background-color: #b3b3b3; font-weight: bold;">Observações gerais</td></tr>
        ');

        foreach ($observacoes_gerais->get() as $observacao_geral) {
            foreach (explode("\r\n", $observacao_geral->observacoes) as $linhas) {
                $this->documentBody .= ('<tr><td colspan="3">' . $linhas . '</td></tr>');
            }
        }

        $this->documentBody .= ('
            </table>
        </div>
        ');

        $this->documentBody .= ('
        <div class="content no_border_bottom">
            <table cellpadding="0" cellspacing="0" class="table_01 table_02">
                <tr><td colspan="3" style="background-color: #b3b3b3; font-weight: bold;">Informações sobre as aplicações</td></tr>
                <tr><td colspan="3">Solicitado por:</td></tr>
                <tr><td colspan="3">Pesado por:</td></tr>
                <tr><td colspan="3">Enviado p/ o campo por:</td></tr>
                <tr><td colspan="3">Tratorista:</td></tr>
                <tr><td colspan="3">Conferência noturna:</td></tr>
                <tr><td colspan="3">Observações do plantonista:</td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
            </table>
        </div>
        ');

        $this->WriteHTML($this->documentCss, HTMLParserMode::HEADER_CSS);
        $this->WriteHTML($this->documentBody, HTMLParserMode::HTML_BODY);
    }
}
