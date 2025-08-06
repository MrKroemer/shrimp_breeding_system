<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EstoqueEntradas;
use App\Models\EstoqueEntradasNotas;
use App\Models\VwEstoqueDisponivel;
use App\Models\VwProdutos;
use App\Models\NotasFiscais;
use App\Models\Filiais;
use App\Models\HistoricoImportacoes;
use App\Models\TaxasCustos;
use League\Csv\Reader;

class EstoqueEntradasController extends Controller
{
    private $rowsPerPage = 10;
    private $tipos_origens = [
        1   => 'Nota fiscal',
        2   => 'Importação',
        3   => 'Cancelamento',
    ];

    public function listingEstoqueEntradas()
    {
        $estoque_entradas = EstoqueEntradas::listing($this->rowsPerPage);

        return view('admin.estoque_entradas.list')
        ->with('estoque_entradas', $estoque_entradas)
        ->with('tipos_origens', $this->tipos_origens);
    }

    public function searchEstoqueEntradas(Request $request)
    {
        $formData = $request->except(['_token']);

        $estoque_entradas = EstoqueEntradas::search($formData, $this->rowsPerPage);

        return view('admin.estoque_entradas.list')
        ->with('formData', $formData)
        ->with('estoque_entradas', $estoque_entradas)
        ->with('tipos_origens', $this->tipos_origens);
    }


   public static function storeEstoqueEntradas(NotasFiscais $nota_fiscal)
    {
        foreach ($nota_fiscal->nota_fiscal_itens as $item) {

            $valor_total    = ($item->quantidade * $item->valor_unitario) - $item->valor_desconto;
            $quantidade     = ($item->quantidade * $item->produto->unidade_razao);
            $valor_unitario = ($valor_total / $quantidade);

            if($item->produto_id == 189){

                $taxas_custos = new TaxasCustos();

                $orderTax = $taxas_custos->orderByDesc('id')->first();
                $custoTr = ($orderTax->custovar_racao) + ($orderTax->custofix_racao);
                $valor_unitario = $custoTr;
            }

    
            $entrada = EstoqueEntradas::create([
                'data_movimento' => $nota_fiscal->data_movimento,
                'quantidade'     => $quantidade,
                'valor_unitario' => $valor_unitario,
                'valor_total'    => $valor_total,
                'produto_id'     => $item->produto_id,
                'filial_id'      => session('_filial')->id,
                'usuario_id'     => auth()->user()->id,
                'tipo_origem'    => 1, // Nota fiscal
            ]);

            if ($entrada instanceof EstoqueEntradas) {

                EstoqueEntradasNotas::create([
                    'estoque_entrada_id' => $entrada->id,
                    'nota_fiscal_id'     => $nota_fiscal->id,
                ]);

                continue;

            }

            $entradas_notas = EstoqueEntradasNotas::where('nota_fiscal_id', $nota_fiscal->id)
            ->get();
                
            foreach ($entradas_notas as $entradas_nota) {
                $entradas_nota->delete();
                $entradas_nota->estoque_entrada->delete();
            }

            return false;

        }

        return true;
    }
}
