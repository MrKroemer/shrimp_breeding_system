<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwEstoqueDisponivel;
use App\Models\ProdutosTipos;

class EstoqueDisponivelController extends Controller
{
    private $rowsPerPage = 10;

    public function listingEstoqueDisponivel()
    {
        $estoque_disponiveis = VwEstoqueDisponivel::listing($this->rowsPerPage);
        $produtos_tipos = ProdutosTipos::orderBy('nome')->get();

        return view('admin.estoque_disponivel.list')
        ->with('estoque_disponiveis', $estoque_disponiveis)
        ->with('produtos_tipos',      $produtos_tipos);
    }

    public function searchEstoqueDisponivel(Request $request)
    {
        $formData = $request->except(['_token']);

        $estoque_disponiveis = VwEstoqueDisponivel::search($formData, $this->rowsPerPage);
        $produtos_tipos = ProdutosTipos::orderBy('nome')->get();

        return view('admin.estoque_disponivel.list')
        ->with('formData',            $formData)
        ->with('estoque_disponiveis', $estoque_disponiveis)
        ->with('produtos_tipos',      $produtos_tipos);
    }
}
