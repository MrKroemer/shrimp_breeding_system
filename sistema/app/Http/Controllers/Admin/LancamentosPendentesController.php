<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwMovimentos;
use App\Models\Tanques;

class LancamentosPendentesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingLancamentosPendentes()
    {
        $lancamentos_pendentes = VwMovimentos::listing($this->rowsPerPage);

        //dd($lancamentos_pendentes);

        $tanques =$tanques = Tanques::where('filial_id', session('_filial')->id)
        ->orderBy('sigla', 'asc')
        ->get();

        return view('admin.lancamentos_pendentes.list')
        ->with('lancamentos_pendentes', $lancamentos_pendentes)
        ->with('tanques', $tanques);
    }

    public function searchLancamentosPendentes(Request $request)
    {
        $formData = $request->except(['_token']);

        $lancamentos_pendentes = VwMovimentos::search($formData, $this->rowsPerPage);

        $tanques =$tanques = Tanques::where('filial_id', session('_filial')->id)
        ->orderBy('sigla', 'asc')
        ->get();

        return view('admin.lancamentos_pendentes.list')
        ->with('formData',              $formData)
        ->with('lancamentos_pendentes', $lancamentos_pendentes)
        ->with('tanques',               $tanques);
    }
}
