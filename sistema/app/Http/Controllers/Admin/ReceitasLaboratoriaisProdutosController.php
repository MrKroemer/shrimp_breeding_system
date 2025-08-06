<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReceitasLaboratoriaisProdutosCreateFormRequest;
use App\Models\ReceitasLaboratoriais;
use App\Models\ReceitasLaboratoriaisProdutos;
use App\Models\ReceitasLaboratoriaisPeriodos;
use App\Models\VwEstoqueDisponivel;
use App\Http\Controllers\Util\DataPolisher;


class ReceitasLaboratoriaisProdutosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingReceitasLaboratoriaisProdutos(int $receita_laboratorial_id, int $receita_laboratorial_periodo_id)
    {
        $receitas_laboratoriais_produtos = ReceitasLaboratoriaisProdutos::listing($this->rowsPerPage, [
            'receita_laboratorial_id'         => $receita_laboratorial_id,
            'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo_id,
        ]);

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->whereNotIn('produto_tipo_id', [2, 5]) // RAÇÔES, PÓS-LARVAS (CAMARÃO)
        ->orderBy('produto_nome')
        ->get();

        $receita_laboratorial         = ReceitasLaboratoriais::find($receita_laboratorial_id);
        $receita_laboratorial_periodo = ReceitasLaboratoriaisPeriodos::find($receita_laboratorial_periodo_id);
        
        return view('admin.receitas_laboratoriais_produtos.list')
        ->with('produtos',                        $produtos)
        ->with('receita_laboratorial',            $receita_laboratorial)
        ->with('receita_laboratorial_periodo',    $receita_laboratorial_periodo)
        ->with('receitas_laboratoriais_produtos', $receitas_laboratoriais_produtos);
    }

    public function storeReceitasLaboratoriaisProdutos(ReceitasLaboratoriaisProdutosCreateFormRequest $request, int $receita_laboratorial_id, int $receita_laboratorial_periodo_id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);
        
        $data['receita_laboratorial_id']         = $receita_laboratorial_id;
        $data['receita_laboratorial_periodo_id'] = $receita_laboratorial_periodo_id;

        $insert = ReceitasLaboratoriaisProdutos::create($data);

        if ($insert) {
            return redirect()->route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos', [
                'receita_laboratorial_id'         => $receita_laboratorial_id, 
                'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo_id
            ])
            ->with('success', 'Produto da receita laboratorial cadastrada com sucesso!');
        }

        return redirect()->back()
        ->with('quantidade',                      $data['quantidade'])
        ->with('produto_id',                      $data['produto_id'])
        ->with('receita_laboratorial_id',         $receita_laboratorial_id)
        ->with('receita_laboratorial_periodo_id', $receita_laboratorial_periodo_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o produto da receita laboratorial. Tente novamente!');
    }

    public function editReceitasLaboratoriaisProdutos(int $receita_laboratorial_id, int $receita_laboratorial_periodo_id, int $id)
    {
        $data = ReceitasLaboratoriaisProdutos::find($id);

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->whereNotIn('produto_tipo_id', [2, 5]) // RAÇÔES, PÓS-LARVAS (CAMARÃO)
        ->orderBy('produto_nome')
        ->get();

        $receita_laboratorial         = ReceitasLaboratoriais::find($receita_laboratorial_id);
        $receita_laboratorial_periodo = ReceitasLaboratoriaisPeriodos::find($receita_laboratorial_periodo_id);
        
        return view('admin.receitas_laboratoriais_produtos.edit')
        ->with('id',                           $id)
        ->with('quantidade',                   $data['quantidade'])
        ->with('produto_id',                   $data['produto_id'])
        ->with('receita_laboratorial',         $receita_laboratorial)
        ->with('receita_laboratorial_periodo', $receita_laboratorial_periodo)
        ->with('produtos',                     $produtos);
    }

    public function updateReceitasLaboratoriaisProdutos(ReceitasLaboratoriaisProdutosCreateFormRequest $request, int $receita_laboratorial_id, int $receita_laboratorial_periodo_id, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $update = ReceitasLaboratoriaisProdutos::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos', [
                'receita_laboratorial_id'         => $receita_laboratorial_id, 
                'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo_id
            ])
            ->with('success', 'Produto da receita laboratorial atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id',                              $id)
        ->with('receita_laboratorial_id',         $receita_laboratorial_id)
        ->with('receita_laboratorial_periodo_id', $receita_laboratorial_periodo_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o produto da receita laboratorial. Tente novamente!');
    }

    public function removeReceitasLaboratoriaisProdutos(int $receita_laboratorial_id, int $receita_laboratorial_periodo_id, int $id)
    {
        $data = ReceitasLaboratoriaisProdutos::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos', [
                'receita_laboratorial_id'         => $receita_laboratorial_id, 
                'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo_id
            ])
            ->with('success', 'Produto da receita laboratorial excluido com sucesso!');
        } 

        return redirect()->route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos', [
                'receita_laboratorial_id'         => $receita_laboratorial_id, 
                'receita_laboratorial_periodo_id' => $receita_laboratorial_periodo_id
        ])
        ->with('error', 'Ocorreu um erro ao excluir o produto da receita laboratorial. Tente novamente!');
    }
}
