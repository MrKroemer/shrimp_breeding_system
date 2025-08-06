<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ColetasParametrosImportacoes;
use App\Models\Tanques;
use App\Models\VwEstoqueDisponivel;
use App\Models\VwProdutos;
use League\Csv\Reader;

use App\Models\EstoqueEntradas; //###
use App\Models\HistoricoImportacoes; //###

class ColetasParametrosImportacoesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingColetasParametrosImportacoes()
    {
        $coletas_parametros_importacoes = ColetasParametrosImportacoes::listing($this->rowsPerPage);

        return view('admin.coletas_parametros_importacoes.list')
        ->with('coletas_parametros_importacoes', $coletas_parametros_importacoes);
    }

    public function searchColetasParametrosImportacoes(Request $request)
    {
        $formData = $request->except(['_token']);

        $coletas_parametros_importacoes = ColetasParametrosImportacoes::search($formData, $this->rowsPerPage);

        return view('admin.coletas_parametros_importacoes.list')
        ->with('formData', $formData)
        ->with('coletas_parametros_importacoes', $coletas_parametros_importacoes);
    }

    public function removeColetasParametrosImportacoes(int $id)
    {
        $data = ColetasParametrosImportacoes::find($id);

        if ($data->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        } 

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function importColetasParametrosImportacoes(Request $request)
    {
        $tanques =  Tanques::where('filial_id', session('_filial')->id)
        ->where('situacao', 'ON')
        ->whereIn('tipo_tanque_id', [1,3,6,7]) // viveiro camarões, reservatórios água tratada , viveiros peixes, reservatórios agua salgada 
        ->get();

        $importacoes = ColetasParametrosImportacoes::where('filial_id', session('_filial')->id)
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
                'Data Hora',
                '"Nome do Local"',
                '"Latitude (°)"',
                '"Longitude (°)"',
                '"NS do Dispositivo"',
                '"Concentração RDO (mg/L) (619290)"',
                '"Salinidade (ppt) (617924)"',
                '"pH (pH) (612335)"',
                '"Temperatura (°C) (648503)"',
                '"ORP (mV) (612335)"',
                '"Total de Sólidos Dissolvidos (ppt) (617924)"',
                '"Densidade (g/cm³) (617924)"',
                '"Resistividade (Ω⋅cm) (617924)"',
                '"Condutividade Real (µS/cm) (617924)"',
                '"Condutividade Específica (µS/cm) (617924)"',
                '"pH mV (mV) (612335)"',
                '"Pressão Parcial de Oxigénio (Torr) (619290)"',
                '"Saturação RDO (%Sat) (619290)"',
                '"Pressão (psi) (623842)"',
                '"Profundidade (cm) (623842)"',
                '"Profundidade até à Água (cm) (623842)"',
                '"Voltagem Externa (V) (648503)"',
                '"NS do Dispositivo"',
                '"Pressão Barométrica (mbar) (584554)"',
                '"Temperatura (°C) (584554)"',
            ];

            foreach ($csvHeader as $key => $value) {
                $csvHeader[$key] = utf8_encode($value);
            }

            dd($file_rows);

            if ($this->checkCsvHeader($csvHeader, $neededHeader)) {

                $file_rows = $file_rows->getRecords();

                foreach ($file_rows as $row) {

                    foreach ($row as $column => $value) {
                        $row[utf8_encode($column)] = utf8_encode($value);
                    }

                    $coleta = VwProdutos::where('codigo_externo', $row['Código Produto'])
                    ->first();  //##

                    $produto = VwProdutos::where('filial_id', session('_filial')->id)
                        ->where('codigo_externo', $row['Código Produto'])
                        ->first(); //##


                    if ($coleta instanceof VwProdutos) {

                        $data_movimento = date('Y-m-d');
                        $quantidade     = $row['Qtd. Estoque'];
                        $valor_unitario = $row['Custo Médio'];
                        $valor_total    = ($quantidade * $valor_unitario);

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
                            'tipo_origem'    => 2, // Importação
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
                
                /*$data_movimento = date('Y-m-d');// (( d-_-b ))
                $historico['data_importacao'] = $data_movimento;
                $historico['filial_id']       = session('_filial')->id;
                $historico['usuario_id']      = auth()->user()->id;

                HistoricoImportacoes::create($historico);

                return $redirect->with('success', 'Importação realizada com sucesso');*/

            }
            
        }
        
               //return $redirect->with('warning', 'O arquivo não foi selecionado ou possui um formato invalido.');
        
    }

    private function checkCsvHeader(array $csvHeader, array $neededHeader) 
    {
        sort($csvHeader);
        sort($neededHeader);

        return ($csvHeader == $neededHeader);
    }
}
