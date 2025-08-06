<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Carbon;
use League\Csv\Writer;
use App\Http\Controllers\Controller;
use App\Models\EstoqueSaidas;
use App\Models\VwEstoqueSaidas;
use App\Models\HistoricoExportacoes;
use App\Models\Ciclos;
use DB;

class EstoqueSaidasController extends Controller
{
    private $rowsPerPage = 10;
    private $tipos_destinos = [
        1 => 'Preparação',
        2 => 'Povoamento',
        3 => 'Arraçoamento',
        4 => 'Manejo',
        5 => 'Avulsa',
        6 => 'Descarte',
        7 => 'Estorno',
    ];

    public function listingEstoqueSaidas()
    {
        $estoque_saidas = VwEstoqueSaidas::listing($this->rowsPerPage);

        return view('admin.estoque_saidas.list')
        ->with('estoque_saidas', $estoque_saidas)
        ->with('tipos_destinos', $this->tipos_destinos);
    }

    public function searchEstoqueSaidas(Request $request)
    {
        $formData = $request->except(['_token']);

        $estoque_saidas = VwEstoqueSaidas::search($formData, $this->rowsPerPage);

        return view('admin.estoque_saidas.list')
        ->with('formData', $formData)
        ->with('estoque_saidas', $estoque_saidas)
        ->with('tipos_destinos', $this->tipos_destinos);
    }

}
