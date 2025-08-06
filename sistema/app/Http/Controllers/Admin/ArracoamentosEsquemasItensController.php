<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArracoamentosRacoes;
use App\Models\ArracoamentosAlimentacoes;
use App\Models\ArracoamentosEsquemas;
use App\Models\Produtos;

class ArracoamentosEsquemasItensController extends Controller
{
    private $rowsPerPage = 10;

    public function listingArracoamentosEsquemasItens(int $arracoamento_perfil_id, int $arracoamento_esquema_id)
    {
        $data = ['arracoamento_esquema_id' => $arracoamento_esquema_id];

        $arracoamentos_racoes       = ArracoamentosRacoes::listing($this->rowsPerPage, $data);
        $arracoamentos_alimentacoes = ArracoamentosAlimentacoes::listing($this->rowsPerPage, $data);

        $esquema = ArracoamentosEsquemas::where('id', $arracoamento_esquema_id)->first();
        $porcentagem_racoes       = $esquema->porcentagem_racaos();
        $porcentagem_alimentacoes = $esquema->porcentagem_alimentacoes();
        
        $produtos = Produtos::ativos()
        ->where('produto_tipo_id', 2)
        ->whereNotIn('id', 
            ArracoamentosRacoes::where('arracoamento_esquema_id', $arracoamento_esquema_id)
            ->get(['produto_id'])
        )
        ->orderBy('nome', 'asc')
        ->get(['id', 'nome']);

        return view('admin.arracoamentos_esquemas_itens.list')
        ->with('porcentagem_racoes',         $porcentagem_racoes)
        ->with('porcentagem_alimentacoes',   $porcentagem_alimentacoes)
        ->with('produtos',                   $produtos)
        ->with('arracoamentos_racoes',       $arracoamentos_racoes)
        ->with('arracoamentos_alimentacoes', $arracoamentos_alimentacoes)
        ->with('arracoamento_perfil_id',     $arracoamento_perfil_id)
        ->with('arracoamento_esquema_id',    $arracoamento_esquema_id);
    }
}
