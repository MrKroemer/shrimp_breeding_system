<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NotasFiscaisItensCreateFormRequest;
use App\Models\NotasFiscaisItens;
use App\Models\NotasFiscais;
use App\Models\Produtos;
use App\Http\Controllers\Util\DataPolisher;

class NotasFiscaisItensController extends Controller
{
    private $rowsPerPage = 10;

    public function listingNotasFiscaisItens(Request $request, int $nota_fiscal_id)
    {
        $data = $request->only(['redirectBack']);

        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $nota_fiscal = NotasFiscais::find($nota_fiscal_id);

        $notas_fiscais_itens = NotasFiscaisItens::listing($this->rowsPerPage, [
            'nota_fiscal_id' => $nota_fiscal_id,
        ]);

        $route = $this->defineRouteNotasFiscaisItens($nota_fiscal);

        return view('admin.notas_fiscais_itens.list')
        ->with('notas_fiscais_itens', $notas_fiscais_itens)
        ->with('nota_fiscal_id',      $nota_fiscal_id)
        ->with('nota_fiscal',         $nota_fiscal)
        ->with('route',               $route)
        ->with('redirectBack',        $redirectBack);
    }

    public function searchNotasFiscaisItens(Request $request, int $nota_fiscal_id)
    {
        $formData = $request->except(['_token']);
        
        $redirectBack = 'no';

        if (isset($formData['redirectBack'])) {
            $redirectBack = $formData['redirectBack'];
        }

        $formData['nota_fiscal_id'] = $nota_fiscal_id;

        $nota_fiscal = NotasFiscais::find($nota_fiscal_id);

        $notas_fiscais_itens = NotasFiscaisItens::search($formData, $this->rowsPerPage);

        $route = $this->defineRouteNotasFiscaisItens($nota_fiscal);

        return view('admin.notas_fiscais_itens.list')
        ->with('formData',            $formData)
        ->with('notas_fiscais_itens', $notas_fiscais_itens)
        ->with('nota_fiscal_id',      $nota_fiscal_id)
        ->with('nota_fiscal',         $nota_fiscal)
        ->with('route',               $route)
        ->with('redirectBack',        $redirectBack);
    }

    public function createNotasFiscaisItens(Request $request, int $nota_fiscal_id)
    {
        $data = $request->only(['redirectBack']);

        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $nota_fiscal = NotasFiscais::find($nota_fiscal_id);

        $produtos = Produtos::where('filial_id', session('_filial')->id)->orderBy('nome')->get();

        return view('admin.notas_fiscais_itens.create')
        ->with('produtos',       $produtos)
        ->with('nota_fiscal_id', $nota_fiscal_id)
        ->with('nota_fiscal',    $nota_fiscal)
        ->with('redirectBack',   $redirectBack);
    }

    public function storeNotasFiscaisItens(NotasFiscaisItensCreateFormRequest $request, int $nota_fiscal_id)
    {
        $data = $request->except(['_token']);
        
        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

        $data['nota_fiscal_id'] = $nota_fiscal_id;
        $data['usuario_id'] = auth()->user()->id;

        $nota_fiscal_item = NotasFiscaisItens::create($data);

        if ($nota_fiscal_item instanceof NotasFiscaisItens) {

            $route = 'admin.notas_fiscais_entradas.notas_fiscais_itens';
            $params['nota_fiscal_id'] = $nota_fiscal_id;

            if ($redirectBack == 'yes') {
                $params['redirectBack'] = $redirectBack;
            }

            return redirect()->route($route, $params)
            ->with('success', 'Registro salvo com sucesso!');

        }

        return redirect()->back()
        ->with('nota_fiscal_id', $nota_fiscal_id)
        ->with('redirectBack',   $redirectBack)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editNotasFiscaisItens(Request $request, int $nota_fiscal_id, int $id)
    {
        $data = $request->only(['redirectBack']);

        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $nota_fiscal = NotasFiscais::find($nota_fiscal_id);

        $produtos = Produtos::orderBy('nome')->get();

        $data = NotasFiscaisItens::find($id);

        $route = $this->defineRouteNotasFiscaisItens($nota_fiscal);

        $situacao = ($nota_fiscal->situacao == 'A') ? true : false;

        return view('admin.notas_fiscais_itens.edit')
        ->with('id',             $id)
        ->with('quantidade',     $data['quantidade'])
        ->with('valor_unitario', $data['valor_unitario'])
        ->with('valor_frete',    $data['valor_frete'])
        ->with('valor_desconto', $data['valor_desconto'])
        ->with('produto_id',     $data['produto_id'])
        ->with('situacao',       $situacao)
        ->with('produtos',       $produtos)
        ->with('nota_fiscal_id', $nota_fiscal_id)
        ->with('nota_fiscal',    $nota_fiscal)
        ->with('route',          $route)
        ->with('redirectBack',   $redirectBack);
    }

    public function updateNotasFiscaisItens(NotasFiscaisItensCreateFormRequest $request, int $nota_fiscal_id, int $id)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

        $data['nota_fiscal_id'] = $nota_fiscal_id;
        $data['usuario_id'] = auth()->user()->id;

        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $update = NotasFiscaisItens::find($id);

        if ($update->update($data)) {

            $route = 'admin.notas_fiscais_entradas.notas_fiscais_itens';
            $params['nota_fiscal_id'] = $nota_fiscal_id;

            if ($redirectBack == 'yes') {
                $params['redirectBack'] = $redirectBack;
            }

            return redirect()->route($route, $params)
            ->with('success', 'Registro salvo com sucesso!');

        }

        return redirect()->back()
        ->with('id',              $id)
        ->with('nota_fiscal_id',  $nota_fiscal_id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeNotasFiscaisItens(Request $request, int $nota_fiscal_id, int $id)
    {
        $data = $request->only(['redirectBack']);

        $redirectBack = 'no';

        if (isset($data['redirectBack'])) {
            $redirectBack = $data['redirectBack'];
        }

        $data = NotasFiscaisItens::find($id);

        if ($data->nota_fiscal->situacao == 'A') {
            
            $delete = $data->delete();

            if ($delete) {

                $route = 'admin.notas_fiscais_entradas.notas_fiscais_itens';
                $params['nota_fiscal_id'] = $nota_fiscal_id;

                if ($redirectBack == 'yes') {
                    $params['redirectBack'] = $redirectBack;
                }

                return redirect()->route($route, $params)
                ->with('success', 'Registro excluído com sucesso!');
            } 
            
        }
        
        return redirect()->route('admin.notas_fiscais_entradas.notas_fiscais_itens', ['nota_fiscal_id' => $nota_fiscal_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function viewNotasFiscaisItens(Request $request, int $nota_fiscal_id, int $id)
    {
        return $this->editNotasFiscaisItens($request, $nota_fiscal_id, $id);
    }

    public function defineRouteNotasFiscaisItens($nota_fiscal)
    {
        $route = '';

        switch ($nota_fiscal->situacao) {

            case 'A': case 'F':
                $route = 'notas_fiscais_entradas';
                break;
            case 'E':
                $route = 'notas_fiscais_estornos';
                break;

        }

        return $route;
    }
}
