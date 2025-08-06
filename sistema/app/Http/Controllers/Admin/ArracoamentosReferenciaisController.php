<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArracoamentosReferenciais;
use App\Models\ArracoamentosClimas;
use App\Http\Requests\ArracoamentosReferenciaisCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class ArracoamentosReferenciaisController extends Controller
{
    private $rowsPerPage = 10;

    public function listingArracoamentosReferenciais(int $arracoamento_clima_id)
    {
        $arracoamentos_referenciais = ArracoamentosReferenciais::listing($this->rowsPerPage, [
            'arracoamento_clima_id' => $arracoamento_clima_id
        ]);

        $arracoamento_clima = ArracoamentosClimas::find($arracoamento_clima_id);

        return view('admin.arracoamentos_referenciais.list')
        ->with('arracoamentos_referenciais', $arracoamentos_referenciais)
        ->with('arracoamento_clima_id',      $arracoamento_clima_id)
        ->with('arracoamento_clima',         $arracoamento_clima);
    }

    public function searchArracoamentosReferenciais(Request $request, int $arracoamento_clima_id)
    {
        $formData = $request->except(['_token']);
        $formData['arracoamento_clima_id'] = $arracoamento_clima_id;

        $arracoamentos_referenciais = ArracoamentosReferenciais::search($formData, $this->rowsPerPage);

        $arracoamento_clima = ArracoamentosClimas::find($arracoamento_clima_id);

        return view('admin.arracoamentos_referenciais.list')
        ->with('formData',                   $formData)
        ->with('arracoamentos_referenciais', $arracoamentos_referenciais)
        ->with('arracoamento_clima_id',      $arracoamento_clima_id)
        ->with('arracoamento_clima',         $arracoamento_clima);
    }

    public function createArracoamentosReferenciais(int $arracoamento_clima_id)
    {
        $arracoamento_clima = ArracoamentosClimas::find($arracoamento_clima_id);

        return view('admin.arracoamentos_referenciais.create')
        ->with('arracoamento_clima_id',      $arracoamento_clima_id)
        ->with('arracoamento_clima',         $arracoamento_clima);
    }

    public function storeArracoamentosReferenciais(ArracoamentosReferenciaisCreateFormRequest $request, int $arracoamento_clima_id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['arracoamento_clima_id'] = $arracoamento_clima_id;

        $insert = ArracoamentosReferenciais::create($data);

        if ($insert) {
            return redirect()->route('admin.arracoamentos_climas.arracoamentos_referenciais', ['arracoamento_clima_id' => $arracoamento_clima_id])
            ->with('success', 'Referencial de alimentação cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('dias_cultivo',          $data['dias_cultivo'])
        ->with('peso_medio',            $data['peso_medio'])
        ->with('porcentagem',           $data['porcentagem'])
        ->with('crescimento',           $data['crescimento'])
        ->with('observacoes',           $data['observacoes'])
        ->with('arracoamento_clima_id', $arracoamento_clima_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o registro. Tente novamente!');
    }

    public function editArracoamentosReferenciais(int $arracoamento_clima_id, int $id)
    {
        $data = ArracoamentosReferenciais::find($id);

        $arracoamento_clima = ArracoamentosClimas::find($arracoamento_clima_id);

        return view('admin.arracoamentos_referenciais.edit')
        ->with('id',                    $id)
        ->with('dias_cultivo',          $data['dias_cultivo'])
        ->with('peso_medio',            $data['peso_medio'])
        ->with('porcentagem',           $data['porcentagem'])
        ->with('crescimento',           $data['crescimento'])
        ->with('observacoes',           $data['observacoes'])
        ->with('arracoamento_clima_id', $arracoamento_clima_id)
        ->with('arracoamento_clima',    $arracoamento_clima);
    }

    public function updateArracoamentosReferenciais(ArracoamentosReferenciaisCreateFormRequest $request, int $arracoamento_clima_id, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);
        
        $data['arracoamento_clima_id'] = $arracoamento_clima_id;

        $update = ArracoamentosReferenciais::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.arracoamentos_climas.arracoamentos_referenciais', ['arracoamento_clima_id' => $arracoamento_clima_id])
            ->with('success', 'Referencial de alimentação atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id',                    $id)
        ->with('arracoamento_clima_id', $arracoamento_clima_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o registro. Tente novamente!');
    }

    public function removeArracoamentosReferenciais(int $arracoamento_clima_id, int $id)
    {
        $data = ArracoamentosReferenciais::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.arracoamentos_climas.arracoamentos_referenciais', ['arracoamento_clima_id' => $arracoamento_clima_id])
            ->with('success', 'Referencial de alimentação excluido com sucesso!');
        }
        
        return redirect()->route('admin.arracoamentos_climas.arracoamentos_referenciais', ['arracoamento_clima_id' => $arracoamento_clima_id])
        ->with('error', 'Oops! Algo de errado ocorreu ao excluir o registro. Tente novamente!');
    }
}
