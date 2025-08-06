<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotasFiscais;
use App\Models\ClientesFornecedores;
use App\Models\Filiais;
use App\Http\Requests\NotasFiscaisEntradasCreateFormRequest;
use App\Http\Requests\NotasFiscaisEntradasEditFormRequest;
use App\Http\Controllers\Admin\EstoqueEntradasController;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Controllers\Util\RouteManipulator;
use Carbon\Carbon;

class NotasFiscaisEntradasController extends Controller
{
    private $rowsPerPage = 10;

    public function listingNotasFiscaisEntradas()
    {
        $notas_fiscais = NotasFiscais::listing($this->rowsPerPage, [
            'situacao'  => 'A|F',
            'filial_id' => session('_filial')->id,
        ]);

        return view('admin.notas_fiscais_entradas.list')
        ->with('notas_fiscais', $notas_fiscais);
    }

    public function searchNotasFiscaisEntradas(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['situacao']  = 'A|F';
        $formData['filial_id'] = session('_filial')->id;

        $notas_fiscais = NotasFiscais::search($formData, $this->rowsPerPage);

        return view('admin.notas_fiscais_entradas.list')
        ->with('formData',      $formData)
        ->with('notas_fiscais', $notas_fiscais);
    }

    public function createNotasFiscaisEntradas(Request $request)
    {
        $data = $request->only(['redirectBack']);

        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $fornecedores = ClientesFornecedores::where('tipo', 'F')->orWhere('tipo', 'C|F')->get();
        $clientes     = ClientesFornecedores::where('tipo', 'C')->orWhere('tipo', 'C|F')->get();

        return view('admin.notas_fiscais_entradas.create')
        ->with('fornecedores', $fornecedores)
        ->with('clientes',     $clientes)
        ->with('redirectBack', $redirectBack);
    }

    public function storeNotasFiscaisEntradas(NotasFiscaisEntradasCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $cliente = ClientesFornecedores::where('id', $data['cliente_id'])->first();

        if (! session('_filial')->confirmaCnpj($cliente->cnpj)) {
            return redirect()->back()->withInput()
            ->with('error', 'O CNPJ do cliente informado na nota fiscal, não corresponde com o da atual filial.');
        }
        
        $data['chave']    = preg_replace('/\s+/','',$data['chave']);
        $data['situacao'] = 'A';
        $data['usuario_id'] = auth()->user()->id;
        $data['filial_id']  = session('_filial')->id;

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);
        
        $nota_fiscal = NotasFiscais::create($data);

        if ($nota_fiscal instanceof NotasFiscais) {

            $route = 'admin.notas_fiscais_entradas';
            $param = [];

            if ($redirectBack == 'yes') {
                $route = 'admin.notas_fiscais_entradas.notas_fiscais_itens.to_create';
                $param = ['nota_fiscal_id' => $nota_fiscal->id, 'redirectBack' => $redirectBack];
            }

            return redirect()->route($route, $param)
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editNotasFiscaisEntradas(Request $request, int $id)
    {
        $data = $request->only(['redirectBack']);

        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $data = NotasFiscais::find($id);

        $fornecedores = ClientesFornecedores::where('tipo', 'F')->orWhere('tipo', 'C|F')->get();
        $clientes     = ClientesFornecedores::where('tipo', 'C')->orWhere('tipo', 'C|F')->get();

        $situacao = ($data->situacao == 'A') ? true : false;

        return view('admin.notas_fiscais_entradas.edit')
        ->with('id',              $id)
        ->with('chave',           $data['chave'])
        ->with('numero',          $data['numero'])
        ->with('data_emissao',    $data->data_emissao())
        ->with('valor_total',     $data['valor_total'])
        ->with('valor_frete',     $data['valor_frete'])
        ->with('valor_desconto',  $data['valor_desconto'])
        ->with('icms_desonerado', $data['icms_desonerado'])
        ->with('fornecedor_id',   $data['fornecedor_id'])
        ->with('cliente_id',      $data['cliente_id'])
        ->with('data_movimento',  $data->data_movimento())
        ->with('situacao',        $situacao)
        ->with('fornecedores',    $fornecedores)
        ->with('clientes',        $clientes)
        ->with('redirectBack',    $redirectBack);
    }

    public function updateNotasFiscaisEntradas(NotasFiscaisEntradasEditFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $cliente = ClientesFornecedores::where('id', '=', $data['cliente_id'])->first(['cnpj']);

        if (! session('_filial')->confirmaCnpj($cliente->cnpj)) {
            return redirect()->back()->with('id', $id)
            ->with('error', 'O CNPJ do cliente informado na nota fiscal, não corresponde com o da atual filial.');
        }

        $data['chave'] = preg_replace('/\s+/','',$data['chave']);
        $data['usuario_id'] = auth()->user()->id;

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

        $update = NotasFiscais::find($id);

        if ($update->update($data)) {

            $route = 'admin.notas_fiscais_entradas';
            $param = [];

            if ($redirectBack == 'yes') {
                $route = 'admin.notas_fiscais_entradas.notas_fiscais_itens';
                $param = ['nota_fiscal_id' => $id, 'redirectBack' => $redirectBack];
            }

            return redirect()->route($route, $param)
            ->with('success', 'Registro salvo com sucesso!');

        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeNotasFiscaisEntradas(int $id)
    {
        $data = NotasFiscais::find($id);

        if ($data->nota_fiscal_itens->count() > 0) {
            return redirect()->route('admin.notas_fiscais_entradas')
            ->with('error', 'Notas fiscais com itens cadastrados, não podem ser excluidas. Remova os itens e, tente novamente.');
        }

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.notas_fiscais_entradas')
            ->with('success', 'Nota fiscal excluido com sucesso!');
        } 

        return redirect()->route('admin.notas_fiscais_entradas')
        ->with('error', 'Ocorreu um erro ao excluir a nota fiscal. Tente novamente!');
    }

    /**
     * Retorna a view de edição, porem apenas para visualização
     */
    public function viewNotasFiscaisEntradas(Request $request, int $id)
    {
        return $this->editNotasFiscaisEntradas($request, $id);
    }

    /**
     * Adiciona as quantidades e os valores dos itens ao estoque
     */
    public function closeNotasFiscaisEntradas(Request $request, int $id)
    {
        $data = $request->only(['redirectBack']);

        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $nota_fiscal = NotasFiscais::find($id);

        if ($nota_fiscal->situacao == 'A') {

            if (! session('_filial')->confirmaCnpj($nota_fiscal->cliente->cnpj)) {
                return redirect()->back()
                ->with('error', 'O CNPJ do cliente informado na nota fiscal, não corresponde com o da atual filial.');
            }

            $itens_valor_total    = 0;
            $itens_valor_frete    = 0;
            $itens_valor_desconto = 0;

            foreach ($nota_fiscal->nota_fiscal_itens as $item) {
                $itens_valor_total    += ($item->valor_unitario * $item->quantidade);
                $itens_valor_frete    += $item->valor_frete;
                $itens_valor_desconto += $item->valor_desconto;
            }

            $nota_valor_total    = $nota_fiscal->valor_total;
            $nota_valor_frete    = $nota_fiscal->valor_frete;
            $nota_valor_desconto = $nota_fiscal->valor_desconto;

            if (
                round($nota_valor_total, 2) != round($itens_valor_total, 2) ||
                round($nota_valor_frete, 2) != round($itens_valor_frete, 2) ||
                round($nota_valor_desconto, 2) != round($itens_valor_desconto, 2)
            ) {
                return redirect()->back()
                ->with('error', 'Os valores informados na nota fiscal, não estão coerentes com os valores informados nos itens.\\n' .
                    '\\n Total da nota fiscal: R$ ' . round($nota_valor_total, 2) . 
                    '\\n Cálculo dos itens: R$ ' . round($itens_valor_total, 2) . 
                    '\\n Frete da nota fiscal: R$ ' . round($nota_valor_frete, 2) . 
                    '\\n Frete dos itens: R$ ' . round($itens_valor_frete, 2) . 
                    '\\n Desconto da nota fiscal: R$ ' . round($nota_valor_desconto, 2) . 
                    '\\n Desconto dos itens: R$ ' . round($itens_valor_desconto, 2)
                );
            }

            $entrada = EstoqueEntradasController::storeEstoqueEntradas($nota_fiscal);

            if (! $entrada) {
                return redirect()->back()
                ->with('error', 'Algo de errado ocorreu durante a adição dos itens ao estoque. Tente fechar a nota fiscal novamente.');
            }

            $nota_fiscal->situacao = 'F';
            $nota_fiscal->usuario_id = auth()->user()->id;

            if ($nota_fiscal->save()) {

                $route = 'admin.notas_fiscais_entradas';
                $params = [];

                if ($redirectBack == 'yes') {
                    $route = 'admin.notas_fiscais_entradas.redirect_to.lotes.to_create';
                    $params['redirectBack'] = $redirectBack;
                }

                return redirect()->route($route, $params)
                ->with('success', 'Nota fiscal fechada com sucesso! Itens adicionados ao estoque.');

            }

        }

        return redirect()->back()
        ->with('error', 'Não foi possível fechar a nota fiscal!');
    }

    /**
     * Realiza o estorno da nota fical, removendo os itens da contagem de estoque
     */
    public function reverseNotasFiscaisEntradas(int $id)
    {
        $nota_fiscal = NotasFiscais::find($id);

        if ($nota_fiscal->situacao == 'F') {

            if (! session('_filial')->confirmaCnpj($nota_fiscal->cliente->cnpj)) {
                return redirect()->back()
                ->with('error', 'O CNPJ do cliente informado na nota fiscal, não corresponde com o da atual filial.');
            }

            $entradas_canceladas = true;
            
            foreach ($nota_fiscal->estoque_entradas_nota as $item) {
                
                $item->estoque_entrada->tipo_origem = 3;

                if (! $item->estoque_entrada->save()) {
                    $entradas_canceladas = false;
                    break;
                }

            }

            if ($entradas_canceladas) {

                $nota_fiscal->situacao = 'E';
                $nota_fiscal->usuario_id = auth()->user()->id;

                if ($nota_fiscal->save()) {
                    return redirect()->route('admin.notas_fiscais_entradas')
                    ->with('success', 'Nota fiscal estornada com sucesso! Itens removidos do estoque.');
                }

            } else {

                return redirect()->back()
                ->with('error', 'Nota fiscal não estornada! Alguns itens não foram removidos do estoque.');

            }

        }

        return redirect()->back()
        ->with('error', 'Não foi possível estornar a nota fiscal!');
    }

    public function redirectToCreateLotes(Request $request)
    {
        // Redirecionamento para o módulo de LABORATÓRIO
        return RouteManipulator::redirectTo($request, 3, 'admin.lotes.to_create', ['redirectBack' => 'yes']);
    }
}
