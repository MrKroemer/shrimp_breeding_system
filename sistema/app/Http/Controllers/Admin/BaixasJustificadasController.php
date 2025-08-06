<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaixasJustificadas;
use App\Http\Requests\BaixasJustificadasCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class BaixasJustificadasController extends Controller
{
    private $rowsPerPage = 10;

    public function listingBaixasJustificadas()
    {
        $baixas_justificadas = BaixasJustificadas::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        return view('admin.baixas_justificadas.list')
        ->with('baixas_justificadas', $baixas_justificadas);
    }

    public function searchBaixasJustificadas(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $formData = DataPolisher::toPolish($formData);

        $baixas_justificadas = BaixasJustificadas::search($formData, $this->rowsPerPage);

        return view('admin.baixas_justificadas.list')
        ->with('formData',            $formData)
        ->with('baixas_justificadas', $baixas_justificadas);
    }

    public function createBaixasJustificadas()
    {
        return view('admin.baixas_justificadas.create');
    }

    public function storeBaixasJustificadas(BaixasJustificadasCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        if (empty($data['descricao'])) {
            $data['descricao'] = 'Movimentação sem justificativa';
        }
        
        $data = DataPolisher::toPolish($data);

        $baixa_justificada = BaixasJustificadas::create($data);

        if ($baixa_justificada instanceof BaixasJustificadas) {
            return redirect()->route('admin.baixas_justificadas')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->route('admin.baixas_justificadas')
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editBaixasJustificadas(int $id)
    {
        $baixa_justificada = BaixasJustificadas::find($id);

        return view('admin.baixas_justificadas.edit')
        ->with('baixa_justificada', $baixa_justificada);
    }

    public function updateBaixasJustificadas(BaixasJustificadasCreateFormRequest $request, int $id)
    {
        $baixa_justificada = BaixasJustificadas::find($id);

        $data = $request->except(['_token']);

        if (empty($data['descricao'])) {
            $data['descricao'] = 'Movimentação sem justificativa';
        }

        $data['usuario_id'] = auth()->user()->id;
        
        $data = DataPolisher::toPolish($data);

        if ($baixa_justificada->update($data)) {
            return redirect()->back()
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function viewBaixasJustificadas(int $id)
    {
        return $this->editBaixasJustificadas($id);
    }

    public function removeBaixasJustificadas(int $id)
    {
        $baixa_justificada = BaixasJustificadas::find($id);

        if ($baixa_justificada->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluido com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
