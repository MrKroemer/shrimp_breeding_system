<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwValidacoesPendentes;
use App\Models\Tanques;

class ValidacoesPendentesController extends Controller
{
    private $rowsPerPage = 10;

    private $situacoes = [
        'V' => 'Validado',
        'P' => 'Parcialmente validado',
        'N' => 'Não validado',
        'B' => 'Bloqueado',
    ];

    private $tipos_aplicacoes = [
        3 => 'Arraçoamento',
        4 => 'Aplicação de insumo',
        5 => 'Saída avulsa',
    ];

    public function listingValidacoesPendentes()
    {
        $validacoes_pendentes = VwValidacoesPendentes::listing($this->rowsPerPage);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->orderBy('sigla', 'asc')
        ->get();

        $object = new VwValidacoesPendentes();

        return view('admin.validacoes_pendentes.list')
        ->with('validacoes_pendentes', $validacoes_pendentes)
        ->with('tanques',              $tanques)
        ->with('situacoes',            $this->situacoes)
        ->with('tipos_aplicacoes',     $this->tipos_aplicacoes);
    }

    public function searchValidacoesPendentes(Request $request)
    {
        $formData = $request->except(['_token']);

        $validacoes_pendentes = VwValidacoesPendentes::search($formData, $this->rowsPerPage);

        $tanques =$tanques = Tanques::where('filial_id', session('_filial')->id)
        ->orderBy('sigla', 'asc')
        ->get();

        $object = new VwValidacoesPendentes;

        return view('admin.validacoes_pendentes.list')
        ->with('formData',             $formData)
        ->with('validacoes_pendentes', $validacoes_pendentes)
        ->with('tanques',              $tanques)
        ->with('situacoes',            $this->situacoes)
        ->with('tipos_aplicacoes',     $this->tipos_aplicacoes);
    }
}
