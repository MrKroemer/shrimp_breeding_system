<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwEstoqueDisponivel;
use App\Models\SaidasAvulsas;
use App\Models\SaidasAvulsasProdutos;
use App\Models\SaidasAvulsasReceitas;
use App\Models\ReceitasLaboratoriais;
use App\Http\Controllers\Util\DataPolisher;


class SaidasAvulsasProdutosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingSaidasAvulsasProdutos(int $saida_avulsa_id)
    {
        $saida_avulsa = SaidasAvulsas::find($saida_avulsa_id);

        $saida_avulsa_produtos = SaidasAvulsasProdutos::listing($this->rowsPerPage, [
            'saida_avulsa_id' => $saida_avulsa_id
        ]);

        $saida_avulsa_receitas = SaidasAvulsasReceitas::listing($this->rowsPerPage, [
            'saida_avulsa_id' => $saida_avulsa_id
        ]);

        $receitas = ReceitasLaboratoriais::where('receita_laboratorial_tipo_id', 4) // Insumos para manejo
        ->orderBy('nome')
        ->get();

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->where('em_estoque', '>', 0)
        ->whereNotIn('produto_tipo_id', [2, 5]) // RAÇÔES, PÓS-LARVAS (CAMARÃO)
        ->orderBy('produto_nome')
        ->get();

        return view('admin.saidas_avulsas.produtos.list')
        ->with('saida_avulsa',          $saida_avulsa)
        ->with('saida_avulsa_produtos', $saida_avulsa_produtos)
        ->with('saida_avulsa_receitas', $saida_avulsa_receitas)
        ->with('receitas',              $receitas)
        ->with('produtos',              $produtos);
    }

    public function searchSaidasAvulsasProdutos(Request $request, int $saida_avulsa_id)
    {
        $formData = $request->except(['_token']);
        $formData['saida_avulsa_id'] = $saida_avulsa_id;

        $formData = DataPolisher::toPolish($formData);

        $saida_avulsa = SaidasAvulsas::find($saida_avulsa_id);

        $saida_avulsa_produtos = SaidasAvulsasProdutos::search($formData, $this->rowsPerPage);
        $saida_avulsa_receitas = SaidasAvulsasReceitas::search($formData, $this->rowsPerPage);

        $receitas = ReceitasLaboratoriais::where('receita_laboratorial_tipo_id', 4) // Insumos para manejo
        ->orderBy('nome')
        ->get();

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->where('em_estoque', '>', 0)
        ->whereNotIn('produto_tipo_id', [2, 5]) // RAÇÔES, PÓS-LARVAS (CAMARÃO)
        ->orderBy('produto_nome')
        ->get();

        return view('admin.saidas_avulsas.produtos.list')
        ->with('saida_avulsa',           $saida_avulsa)
        ->with('saida_avulsa_produtos',  $saida_avulsa_produtos)
        ->with('saida_avulsa_receitas',  $saida_avulsa_receitas)
        ->with('receitas',               $receitas)
        ->with('produtos',               $produtos);
    }

    public function createSaidasAvulsasProdutos(int $saida_avulsa_id)
    {
        $receitas = ReceitasLaboratoriais::where('receita_laboratorial_tipo_id', 4) // Insumos para manejo
        ->orderBy('nome')
        ->get();

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->where('em_estoque', '>', 0)
        ->whereNotIn('produto_tipo_id', [2, 5]) // RAÇÔES, PÓS-LARVAS (CAMARÃO)
        ->orderBy('produto_nome')
        ->get();

        return view('admin.saidas_avulsas.produtos.create')
        ->with('saida_avulsa_id', $saida_avulsa_id)
        ->with('receitas',        $receitas)
        ->with('produtos',        $produtos);
    }

    public function storeSaidasAvulsasProdutos(Request $request, int $saida_avulsa_id)
    {
        $data = $request->except(['_token']);
        
        $data['saida_avulsa_id'] = $saida_avulsa_id;

        if ($data['produto_id'] > 0) {

            $data['quantidade'] = $data['qtd_produto'] ?: 0;

            $saida_avulsa_produto = SaidasAvulsasProdutos::create($data);

            if ($saida_avulsa_produto instanceof SaidasAvulsasProdutos) {
                return redirect()->route('admin.saidas_avulsas.produtos', ['saida_avulsa_id' => $saida_avulsa_id])
                ->with('success', 'Registro salvo com sucesso!');
            }
        }

        if ($data['receita_laboratorial_id'] > 0) {

            $data['quantidade'] = $data['qtd_receita'] ?: 0;

            $saida_avulsa_receita = SaidasAvulsasReceitas::create($data);

            if ($saida_avulsa_receita instanceof SaidasAvulsasReceitas) {
                return redirect()->route('admin.saidas_avulsas.produtos', ['saida_avulsa_id' => $saida_avulsa_id])
                ->with('success', 'Registro salvo com sucesso!');
            }

        }

        return redirect()->route('admin.saidas_avulsas.produtos', ['saida_avulsa_id' => $saida_avulsa_id])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function updateSaidasAvulsasProdutos(Request $request, int $saida_avulsa_id, int $id)
    {
        $data = $request->except(['_token']);

        $saida_avulsa_produto = SaidasAvulsasProdutos::find($id);

        $saida_avulsa_produto->quantidade = $data['quantidade'];

        if ($saida_avulsa_produto->update()) {
            return redirect()->back()
            ->with('success', 'Quantidade do produto atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Não foi possível atualizar a quantidade do produto.');
    }

    public function removeSaidasAvulsasProdutos(int $saida_avulsa_id, int $id)
    {
        $saida_avulsa_produto = SaidasAvulsasProdutos::find($id);

        if ($saida_avulsa_produto->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluido com sucesso!');
        }
        

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function removeSaidasAvulsasReceitas(int $saida_avulsa_id, int $id)
    {
        $saida_avulsa_receita = SaidasAvulsasReceitas::find($id);

        if ($saida_avulsa_receita->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluido com sucesso!');
        }
      
        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
