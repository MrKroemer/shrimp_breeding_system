<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwEstoqueDisponivel;
use App\Models\BaixasJustificadas;
use App\Models\BaixasJustificadasProdutos;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Requests\BaixasJustificadasProdutosCreateFormRequest;

class BaixasJustificadasProdutosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingBaixasJustificadasProdutos(int $baixa_justificada_id)
    {
        $baixa_justificada = BaixasJustificadas::find($baixa_justificada_id);

        $baixa_justificada_produtos = BaixasJustificadasProdutos::listing($this->rowsPerPage, [
            'baixa_justificada_id' => $baixa_justificada_id
        ]);

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->get();

        return view('admin.baixas_justificadas.produtos.list')
        ->with('baixa_justificada',          $baixa_justificada)
        ->with('baixa_justificada_produtos', $baixa_justificada_produtos)
        ->with('produtos',                   $produtos);
    }

    public function searchBaixasJustificadasProdutos(Request $request, int $baixa_justificada_id)
    {
        $formData = $request->except(['_token']);
        $formData['baixa_justificada_id'] = $baixa_justificada_id;

        $formData = DataPolisher::toPolish($formData);

        $baixa_justificada = BaixasJustificadas::find($baixa_justificada_id);

        $baixa_justificada_produtos = BaixasJustificadasProdutos::search($formData, $this->rowsPerPage);

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->get();

        return view('admin.baixas_justificadas.produtos.list')
        ->with('baixa_justificada',          $baixa_justificada)
        ->with('baixa_justificada_produtos', $baixa_justificada_produtos)
        ->with('produtos',                   $produtos);
    }

    public function createBaixasJustificadasProdutos(int $baixa_justificada_id)
    {
        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->where('em_estoque', '>', 0)
        ->where('produto_tipo_id', '<>', 5) // Pós-larvas
        ->orderBy('produto_nome')
        ->get();

        return view('admin.baixas_justificadas.produtos.create')
        ->with('produtos',             $produtos)
        ->with('baixa_justificada_id', $baixa_justificada_id);
    }

    public function storeBaixasJustificadasProdutos(BaixasJustificadasProdutosCreateFormRequest $request, int $baixa_justificada_id)
    {
        $data = $request->except(['_token']);
        $data['baixa_justificada_id'] = $baixa_justificada_id;

        $data = DataPolisher::toPolish($data);

        $baixa_justificada_produto = BaixasJustificadasProdutos::create($data);

        if ($baixa_justificada_produto instanceof BaixasJustificadasProdutos) {
            return redirect()->route('admin.baixas_justificadas.produtos', ['baixa_justificada_id' => $baixa_justificada_id])
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->route('admin.baixas_justificadas.produtos', ['baixa_justificada_id' => $baixa_justificada_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function updateBaixasJustificadasProdutos(Request $request, int $baixa_justificada_id, int $id)
    {
        $data = $request->except(['_token']);

        $baixa_justificada_produto = BaixasJustificadasProdutos::find($id);

        $baixa_justificada_produto->quantidade = $data['quantidade'];

        if ($baixa_justificada_produto->update()) {
            return redirect()->back()
            ->with('success', 'Quantidade do produto atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Não foi possível atualizar a quantidade do produto.');
    }

    public function removeBaixasJustificadasProdutos(int $baixa_justificada_id, int $id)
    {
        $baixa_justificada_produto = BaixasJustificadasProdutos::find($id);

        if ($baixa_justificada_produto->delete()) {
            return redirect()->route('admin.baixas_justificadas.produtos', ['baixa_justificada_id' => $baixa_justificada_id])
            ->with('success', 'Registro excluido com sucesso!');
        }

        return redirect()->route('admin.baixas_justificadas.produtos', ['baixa_justificada_id' => $baixa_justificada_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
