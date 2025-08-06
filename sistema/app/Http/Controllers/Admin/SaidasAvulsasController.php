<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tanques;
use App\Models\SaidasAvulsas;
use App\Http\Requests\SaidasAvulsasCreateFormRequest;
use App\Http\Requests\SaidasAvulsasUpdateFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class SaidasAvulsasController extends Controller
{
    private $rowsPerPage = 10;
    private $situacoes = [
        'V' => 'Validado',
        'P' => 'Parcialmente validado',
        'N' => 'Não validado',
        'B' => 'Bloqueado',
    ];

    public function listingSaidasAvulsas()
    {
        $saidas_avulsas = SaidasAvulsas::listing($this->rowsPerPage);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->whereNotIn('tanque_tipo_id', [2, 5]) // Berçarios e recirculadores
        ->orderBy('sigla', 'asc')
        ->get();

        return view('admin.saidas_avulsas.list')
        ->with('saidas_avulsas', $saidas_avulsas)
        ->with('tanques',        $tanques)
        ->with('situacoes',      $this->situacoes);
    }

    public function searchSaidasAvulsas(Request $request)
    {
        $formData = $request->except(['_token']);

        $formData = DataPolisher::toPolish($formData);

        $saidas_avulsas = SaidasAvulsas::search($formData, $this->rowsPerPage);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->whereNotIn('tanque_tipo_id', [2, 5]) // Berçarios e recirculadores
        ->orderBy('sigla', 'asc')
        ->get();

        return view('admin.saidas_avulsas.list')
        ->with('formData',       $formData)
        ->with('saidas_avulsas', $saidas_avulsas)
        ->with('tanques',        $tanques)
        ->with('situacoes',      $this->situacoes);
    }

    public function createSaidasAvulsas()
    {
        $tanques = Tanques::tanquesAtivos()
        ->whereNotIn('tanque_tipo_id', [2, 5]) // Berçarios e recirculadores
        ->orderBy('sigla', 'asc')
        ->get();

        return view('admin.saidas_avulsas.create')
        ->with('tanques', $tanques);
    }

    public function storeSaidasAvulsas(SaidasAvulsasCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        if (empty($data['descricao'])) {
            $data['descricao'] = 'Movimentação sem justificativa';
        }
        
        $data = DataPolisher::toPolish($data);

        $saida_avulsa = SaidasAvulsas::create($data);

        if ($saida_avulsa instanceof SaidasAvulsas) {
            return redirect()->route('admin.saidas_avulsas')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->route('admin.saidas_avulsas')
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editSaidasAvulsas(int $id)
    {
        $saida_avulsa = SaidasAvulsas::find($id);

        return view('admin.saidas_avulsas.edit')
        ->with('saida_avulsa', $saida_avulsa);
    }

    public function updateSaidasAvulsas(SaidasAvulsasUpdateFormRequest $request, int $id)
    {
        $saida_avulsa = SaidasAvulsas::find($id);

        $data = $request->except(['_token']);

        if (empty($data['descricao'])) {
            $data['descricao'] = 'Movimentação sem justificativa';
        }

        if (isset($data['tanque_id'])) {
            unset($data['tanque_id']);
        }

        $data['usuario_id'] = auth()->user()->id;
        
        $data = DataPolisher::toPolish($data);

        if ($saida_avulsa->update($data)) {
            return redirect()->back()
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function viewSaidasAvulsas(int $id)
    {
        return $this->editSaidasAvulsas($id);
    }

    public function removeSaidasAvulsas(int $id)
    {
        $saida_avulsa = SaidasAvulsas::find($id);

        if ($saida_avulsa->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluido com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
