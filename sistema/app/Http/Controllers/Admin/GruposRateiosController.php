<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\GruposRateios;
use App\Http\Requests\GruposRateiosCreateFormRequest;

class GruposRateiosController extends Controller
{
    private $rowsPerPage = 1000;

    public function listingGruposRateios()
    {
        $grupos_rateios = GruposRateios::listing($this->rowsPerPage);

        return view('admin.grupos_rateios.list')
        ->with('grupos_rateios', $grupos_rateios);
    }

    public function searchGruposRateios(Request $request)
    {
        $formData = $request->except(['_token']);

        $grupos_rateios = GruposRateios::search($formData, $this->rowsPerPage);

        return view('admin.grupos_rateios.list')
        ->with('formData',       $formData)
        ->with('grupos_rateios', $grupos_rateios);
    }

    public function createGruposRateios()
    {
        return view('admin.grupos_rateios.create');
    }

    public function storeGruposRateios(GruposRateiosCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id'] = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        $grupo_rateio = GruposRateios::create($data);

        if ($grupo_rateio instanceof GruposRateios) {
            return redirect()->route('admin.grupos_rateios')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->route('admin.grupos_rateios')
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editGruposRateios(int $id)
    {
        $data = GruposRateios::find($id);

        return view('admin.grupos_rateios.edit')
        ->with('id',        $data['id'])
        ->with('nome',      $data['nome'])
        ->with('descricao', $data['descricao']);
    }

    public function updateGruposRateios(GruposRateiosCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['usuario_id'] = auth()->user()->id;

        $grupo_rateio = GruposRateios::find($id);

        if ($grupo_rateio->update($data)) {
            return redirect()->route('admin.grupos_rateios')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeGruposRateios(int $id)
    {
        $grupo_rateio = GruposRateios::find($id);

        if ($grupo_rateio->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        } 

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
