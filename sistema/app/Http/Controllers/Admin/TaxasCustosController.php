<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaxasCustosCreateFormRequest;
use App\Models\TaxasCustos;
use App\Http\Controllers\Util\DataPolisher;

class TaxasCustosController extends Controller
{
    private $rowsPerPage = 1000;

    public function listingTaxasCustos()
    {
        $taxas_custos = TaxasCustos::listing($this->rowsPerPage);

        return view('admin.taxas_custos.list')
        ->with('taxas_custos', $taxas_custos);
    }
    
    public function searchTaxasCustos(Request $request)
    {
        $formData = $request->except(['_token']);

        $taxas_custos = TaxasCustos::search($formData, $this->rowsPerPage);

        return view('admin.taxas_custos.list')
        ->with('formData',     $formData)
        ->with('taxas_custos', $taxas_custos);
    }

    public function createTaxasCustos()
    {
        return view('admin.taxas_custos.create');
    }

    public function storeTaxasCustos(TaxasCustosCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);
        
        $data['usuario_id'] = auth()->user()->id;
        $data['filial_id'] = session('_filial')->id;

        $taxa_custo = TaxasCustos::create($data);

        if ($taxa_custo instanceof TaxasCustos) {
            return redirect()->route('admin.taxas_custos')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('data_referencia',   $data['data_referencia'])
        ->with('custo_fixo',        $data['custo_fixo'])
        ->with('custo_energia',     $data['custo_energia'])
        ->with('custo_combustivel', $data['custo_combustivel'])
        ->with('custo_depreciacao', $data['custo_depreciacao'])
        /*->with('racao_fgcb',      $data['racao_fgcb'])
        ->with('racao_starter',     $data['racao_starter'])
        ->with('racao_fgar',        $data['racao_fgar'])
        ->with('bkc_camanor',       $data['bkc_camanor'])
        ->with('starter_peletizada',$data['starter_peletizada'])*/
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editTaxasCustos(int $id)
    {                
        $data = TaxasCustos::find($id);

        return view('admin.taxas_custos.edit')
        ->with('id',                $id)
        ->with('data_referencia',   $data['data_referencia'])
        ->with('custo_fixo',        $data['custo_fixo'])
        ->with('custo_energia',     $data['custo_energia'])
        ->with('custo_combustivel', $data['custo_combustivel'])
        ->with('custo_depreciacao', $data['custo_depreciacao']);
        /*->with('racao_fgcb',        $data['racao_fgcb'])
        ->with('racao_starter',     $data['racao_starter'])
        ->with('racao_fgar',        $data['racao_fgar'])
        ->with('bkc_camanor',       $data['bkc_camanor'])
        ->with('starter_peletizada',$data['starter_peletizada']);*/
    }

    public function updateTaxasCustos(TaxasCustosCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['usuario_id'] = auth()->user()->id;

        $taxa_custo = TaxasCustos::find($id);

        if ($taxa_custo->update($data)) {
            return redirect()->route('admin.taxas_custos')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeTaxasCustos(int $id)
    {
        $data = TaxasCustos::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->back()
            ->with('success', 'Registro excluido com sucesso!');
        } 
        
        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
