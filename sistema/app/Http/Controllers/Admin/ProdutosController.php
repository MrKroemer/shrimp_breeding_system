<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutosCreateFormRequest;
use App\Models\Produtos;
use App\Models\VwProdutos;
use App\Models\ProdutosTipos;
use App\Models\UnidadesMedidas;
use App\Models\ProdutosCodigosExternos;
use App\Http\Controllers\Util\DataPolisher;

class ProdutosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingProdutos()
    {
        $produtos = VwProdutos::listing($this->rowsPerPage);
        $produtos_tipos = ProdutosTipos::orderBy('nome')->get();

        return view('admin.produtos.list')
        ->with('produtos',       $produtos)
        ->with('produtos_tipos', $produtos_tipos);
    }

    public function searchProdutos(Request $request)
    {
        $formData = $request->except(['_token']);

        $produtos = VwProdutos::search($formData, $this->rowsPerPage);
        $produtos_tipos = ProdutosTipos::orderBy('nome')->get();

        return view('admin.produtos.list')
        ->with('formData',       $formData)
        ->with('produtos',       $produtos)
        ->with('produtos_tipos', $produtos_tipos);
    }

    public function createProdutos()
    {
        $produtos_tipos   = ProdutosTipos::orderBy('nome')->get();
        $unidades_medidas = UnidadesMedidas::orderBy('nome')->get();

        return view('admin.produtos.create')
        ->with('produtos_tipos',    $produtos_tipos)
        ->with('unidades_entradas', $unidades_medidas)
        ->with('unidades_saidas',   $unidades_medidas);
    }

    public function storeProdutos(ProdutosCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        $produto = Produtos::create($data);

        if ($produto instanceof Produtos) {

            if (isset($data['codigo_externo'])) {

                $produto->saveCodigoExterno($data['codigo_externo']);

                unset($data['codigo_externo']);

            }

            return redirect()->route('admin.produtos')
            ->with('success', 'Registro salvo com sucesso!');

        }

        return redirect()->back()
        ->with('codigo_externo',     $data['codigo_externo'])
        ->with('nome',               $data['nome'])
        ->with('sigla',              $data['sigla'])
        ->with('codigo_barras',      $data['codigo_barras'])
        ->with('unidade_entrada_id', $data['unidade_entrada_id'])
        ->with('unidade_saida_id',   $data['unidade_saida_id'])
        ->with('unidade_razao',      $data['unidade_razao'])
        ->with('produto_tipo_id',    $data['produto_tipo_id'])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editProdutos(int $id)
    {
        $data = Produtos::find($id);

        $produtos_tipos   = ProdutosTipos::orderBy('nome')->get();
        $unidades_medidas = UnidadesMedidas::orderBy('nome')->get();

        return view('admin.produtos.edit')
        ->with('id',                 $id)
        ->with('codigo_externo',     $data->getCodigoExterno())
        ->with('nome',               $data['nome'])
        ->with('sigla',              $data['sigla'])
        ->with('codigo_barras',      $data['codigo_barras'])
        ->with('unidade_entrada_id', $data['unidade_entrada_id'])
        ->with('unidade_saida_id',   $data['unidade_saida_id'])
        ->with('unidade_razao',      $data['unidade_razao'])
        ->with('produto_tipo_id',    $data['produto_tipo_id'])
        ->with('unidades_entradas',  $unidades_medidas)
        ->with('unidades_saidas',    $unidades_medidas)
        ->with('produtos_tipos',     $produtos_tipos);
    }

    public function updateProdutos(ProdutosCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $produto = Produtos::find($id);

        if ($produto instanceof Produtos) {

            if (isset($data['codigo_externo'])) {

                $produto->saveCodigoExterno($data['codigo_externo']);

                unset($data['codigo_externo']);

            }

            if ($produto->update($data)) {
                return redirect()->route('admin.produtos')
                ->with('success', 'Registro salvo com sucesso!');
            }

        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeProdutos(int $id)
    {
        $produto = Produtos::find($id);

        $codigo_externo = $produto->getCodigoExterno();

        if (! is_null($codigo_externo)) {

            if ($produto->deleteCodigoExterno()) {

                if ($produto->delete()) {
                    return redirect()->route('admin.produtos')
                    ->with('success', 'Registro excluido com sucesso!');
                } else {
                    $produto->saveCodigoExterno($codigo_externo);
                }

            }

        } else {
            
            if ($produto->delete()) {
                return redirect()->route('admin.produtos')
                ->with('success', 'Registro excluido com sucesso!');
            }

        }

        return redirect()->route('admin.produtos')
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function turnProdutos(int $id)
    {
        $data = Produtos::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.produtos')
            ->with('success', 'Produto desabilitado com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.produtos')
        ->with('success', 'Produto habilitado com sucesso!');
    }
}
