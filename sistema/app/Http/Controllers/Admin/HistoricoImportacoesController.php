<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HistoricoImportacoes;
use App\Models\EstoqueEntradas;
use App\Models\TaxasCustos;
use App\Models\VwEstoqueDisponivel;
use App\Models\VwProdutos;
use League\Csv\Reader;

class HistoricoImportacoesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingHistoricoImportacoes()
    {
        $historico_importacoes = HistoricoImportacoes::listing($this->rowsPerPage);

        return view('admin.historico_importacoes.list')
        ->with('historico_importacoes', $historico_importacoes);
    }

    public function searchHistoricoImportacoes(Request $request)
    {
        $formData = $request->except(['_token']);

        $historico_importacoes = HistoricoImportacoes::search($formData, $this->rowsPerPage);

        return view('admin.historico_importacoes.list')
        ->with('formData', $formData)
        ->with('historico_importacoes', $historico_importacoes);
    }

    public function removeHistoricoImportacoes(int $id)
    {
        $data = HistoricoImportacoes::find($id);

        if ($data->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        } 

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function importHistoricoImportacoes(Request $request)
    {
        $importacoes = HistoricoImportacoes::where('filial_id', session('_filial')->id)
        ->where('data_importacao', date('Y-m-d'));

        $redirect = redirect()->back();

        if ($importacoes->count() > 0) {

            $ultima_importacao = $importacoes->get()->sortByDesc('id')->first();

            return $redirect->with('error', "A importação do dia " . date('d/m/Y') . ", já foi realizada pelo usuário {$ultima_importacao->usuario->nome}.");

        }

        if ($request->hasFile('arquivo_importacao') && $request->file('arquivo_importacao')->isValid()) {

            $arquivo_importacao = $request->file('arquivo_importacao');

            if (strtolower($arquivo_importacao->getClientOriginalExtension()) != 'csv') {
                return $redirect->with('warning', 'O arquivo deve possuir a extensão CSV');
            }

            $file_rows = Reader::createFromFileObject($arquivo_importacao->openFile(), 'r');
            $file_rows->setDelimiter(';');
            $file_rows->setHeaderOffset(0);

            $csvHeader = $file_rows->getHeader();
            $neededHeader = [
                'Código Produto',
                'Produto',
                'Un',
                'Qtd. Estoque',
                'Custo Médio',
                'Valor Total',
                //'Local',
                //'Custo Médio',
                //'Fabricado Em',
                //'Lote',
            ];

            foreach ($csvHeader as $key => $value) {
                $csvHeader[$key] = utf8_encode($value);
            }

            if ($this->checkCsvHeader($csvHeader, $neededHeader)) {

                $file_rows = $file_rows->getRecords();
                $data_movimento = date('Y-m-d');

                foreach ($file_rows as $row) {

                    foreach ($row as $column => $value) {
                        $row[utf8_encode($column)] = utf8_encode($value);
                    }

                    $taxas_custos = new TaxasCustos();
                    $orderTax = $taxas_custos
                                ->orderByDesc('id')
                                ->first();
                    $fgcb = $orderTax->racao_fgcb;
                    $starter = $orderTax->racao_starter;
                    $bkc = $orderTax->bkc_camanor;
                    $fgAr = $orderTax->racao_fgar;
                    $peletizada = $orderTax->starter_peletizada;          
                   //$custoTr = ($orderTax->custovar_racao) + ($orderTax->custofix_racao);

                    if (is_numeric($row['Código Produto'])) {

                        $produto = VwProdutos::where('filial_id', session('_filial')->id)
                        ->where('codigo_externo', $row['Código Produto'])
                        ->first();

                       
                        //$valor_unitario = $custoTr;            


                        if ($produto instanceof VwProdutos) {

                            $data_movimento = date('Y-m-d');
                            $quantidade     = floatval($row['Qtd. Estoque']);
                            $valor_unitario = floatval($row['Custo Médio']);
                            $nome           = $row['Produto'];
                            $codigo_externo = $row['Código Produto'];
                           

                           /*if($codigo_externo == '403818'){
                                $valor_unitario = $fgcb;
                                $valor_total = ($quantidade * $fgcb);
                            }
                            if($codigo_externo == '403820'){
                                $valor_unitario = $bkc;
                                $valor_total = ($quantidade * $bkc);
                            }
                            if($codigo_externo == '421291'){
                                $valor_unitario = $starter;
                                $valor_total = ($quantidade * $starter);
                            }
                            if($codigo_externo == '423261'){
                                $valor_unitario = $fgAr;
                                $valor_total = ($quantidade * $fgAr);
                            }
                            if($codigo_externo == '424451'){
                                $valor_unitario = $peletizada;
                                $valor_total = ($quantidade * $peletizada);
                            }*/
                            
                            $valor_total = ($quantidade * $valor_unitario);
                            $disponivel = VwEstoqueDisponivel::verificaDisponibilidade($produto->id);


                            if ($disponivel instanceof VwEstoqueDisponivel) {

                                $quantidade -= $disponivel->em_estoque;
                                
                                 if ($quantidade <= 0) {
                                    continue;
                                }

                                $valor_total = ($quantidade * $valor_unitario);

                            }

                            $dados = [
                                'data_movimento' => $data_movimento,
                                'quantidade'     => $quantidade,
                                'valor_unitario' => $valor_unitario,
                                'valor_total'    => $valor_total,
                                'produto_id'     => $produto->id,
                                'filial_id'      => session('_filial')->id,
                                'usuario_id'     => auth()->user()->id,
                                'tipo_origem'    => 2, //Importação
                            ];

                            $entrada = EstoqueEntradas::create($dados);

                            if ($entrada instanceof EstoqueEntradas) {
                                continue;
                            }

                            $entrada = EstoqueEntradas::where('data_movimento', $data_movimento)
                            ->where('tipo_origem', 2);

                            $entrada->delete();

                            return $redirect->with('error', 'Ocorreu um erro durante a importação. Tente novamente!');


                        }

                        
                    }

                }

                      

                //$data_movimento = date('Y-m-d');
                $historico['data_importacao'] = $data_movimento;
                $historico['filial_id']       = session('_filial')->id;
                $historico['usuario_id']      = auth()->user()->id;

                HistoricoImportacoes::create($historico);
                 

            
                return $redirect->with('success', 'Importação realizada com sucesso');

            }
           
         }

                return $redirect->with('warning', 'O arquivo não foi selecionado ou possui um formato invalido.');
    }

    private function checkCsvHeader(array $csvHeader, array $neededHeader) 
    {
        sort($csvHeader);
        sort($neededHeader);

        return ($csvHeader == $neededHeader);
    }
}
