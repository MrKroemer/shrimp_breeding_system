<?php

    namespace App\Http\Controllers\Reports;
   
    use Mpdf\Mpdf as mPDF;
    use Mpdf\HTMLParserMode;
    use App\Http\Controllers\Util\DataPolisher;
    use App\Model\TanquesTipos;
    use App\Models\Ciclos;


    class CustosTotaisReport extends Mpdf
    {

      private $documentLogo;
      private $documentTitle;
      private $documentHeader;
      private $documentFooter;
      private $documentCss;
      private $documentBody;


      public function __construct(){

        parent::__construct([

            'mode'           => 'utf-8',
            'orientation'    => 'p',
            'default_font'   => 'Arial',
            'margin-left'    => 5,
            'margin-right'   => 5,
            'margin-top'     => 30,
            'margin-bottom'  => 10,
            'margin-header'  => 5,
            'margin-footer'  => 5,
            'temDir'         => storage_path('tmp'),

           ]);

           $this->documentLogo = public_path('img\system_logo.png');
           $this->documentTitle = 'Relatório de Custos Totais';

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
           $this->SetHTMLHeader($this->documentHeader);
           $this->SetHTMLFooter($this->documentFooter);

        
        }

        public function Makedocument(Array $params){

            $data_solicitacao = $params[0];
            $ciclos           = $params[2];

            $itemPerPage = $ciclos->count();


            foreach ($ciclos as $ciclo){

                
            $custo_saidas = $ciclo->custo_fixado($data_final);
            $custo_rateio = $ciclo->custo_saida($data_final);
            $custo_fixado = $ciclo->custo_fixado($data_final);

            $custo_total =  $custo_saidas + $custo_rateio + $custo_fixado->total;
                
                $this->documentBody .= '<div class="content">';

                $this->documentBody .= '<table class="table_02">';
    
                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="5"><i>Consumos realizados no período de <b>' . $data_inicial . '</b> à <b>' . $data_final . '</b></i></td>';
                $this->documentBody .= '</tr>';
    
                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="5" style="border-bottom: 1px solid #000; border-top: 1px solid #000; background-color: #e6e6e6;"><b>' . $ciclo->tanque->sigla .  ' (Nº' . $ciclo->numero . ')</b></td>';
                $this->documentBody .= '</tr>';
    
                $this->documentBody .= '<tr>';
                $this->documentBody .= '<td colspan="3" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">Total geral:</td>';
                $this->documentBody .= '<td colspan="1" style="border-top: 1px solid #000; background-color: #e6e6e6; font-weight: bold;">R$ ' . DataPolisher::numberFormat($custo_total) . '</td>';
                $this->documentBody .= '</tr>';
    
            }

        }
           



    }