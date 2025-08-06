<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EstoqueEstornos;
use App\Models\EstoqueEstornosJustificativas;
use App\Models\Produtos;

class EstoqueEstornosController extends Controller
{
    private $rowsPerPage = 10;
    private $tipos_origens = [
        1 => 'Preparação',
        2 => 'Povoamento',
        3 => 'Arraçoamento',
        4 => 'Manejo',
        5 => 'Avulsa',
        6 => 'Descarte',
    ];

    public function listingEstoqueEstornos()
    {
        $estornos_justificativas = EstoqueEstornosJustificativas::listing($this->rowsPerPage);

        return view('admin.estoque_estornos.list')
        ->with('estornos_justificativas', $estornos_justificativas)
        ->with('tipos_origens', $this->tipos_origens);
    }

    public function searchEstoqueEstornos(Request $request)
    {
        $formData = $request->except(['_token']);

        $estornos_justificativas = EstoqueEstornosJustificativas::search($formData, $this->rowsPerPage);

        return view('admin.estoque_estornos.list')
        ->with('formData',                $formData)
        ->with('estornos_justificativas', $estornos_justificativas)
        ->with('tipos_origens', $this->tipos_origens);
    }

    public function listingEstoqueEstornosProdutos(int $estorno_justificativa_id)
    {
        $estoque_estornos = EstoqueEstornos::listing($this->rowsPerPage, [
            'filial_id'                => session('_filial')->id,
            'estorno_justificativa_id' => $estorno_justificativa_id,
        ]);

        $estorno_justificativa = EstoqueEstornosJustificativas::find($estorno_justificativa_id);

        $produtos = Produtos::orderBy('nome', 'asc')->get();

        return view('admin.estoque_estornos.produtos.list')
        ->with('estoque_estornos',      $estoque_estornos)
        ->with('estorno_justificativa', $estorno_justificativa)
        ->with('produtos',              $produtos);
    }

    public function searchEstoqueEstornosProdutos(Request $request, int $estorno_justificativa_id)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id']                = session('_filial')->id;
        $formData['estorno_justificativa_id'] = $estorno_justificativa_id;

        $estoque_estornos = EstoqueEstornos::search($formData, $this->rowsPerPage);

        $estorno_justificativa = EstoqueEstornosJustificativas::find($estorno_justificativa_id);

        $produtos = Produtos::orderBy('nome', 'asc')->get();

        return view('admin.estoque_estornos.produtos.list')
        ->with('formData',              $formData)
        ->with('estoque_estornos',      $estoque_estornos)
        ->with('estorno_justificativa', $estorno_justificativa)
        ->with('produtos',              $produtos);
    }

}
