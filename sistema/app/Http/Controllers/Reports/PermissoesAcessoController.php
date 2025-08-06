<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Filiais;
use App\Models\Usuarios;
use App\Models\VwPermissoesDeAcesso;

class PermissoesAcessoController extends Controller
{
    public function createPermissoesAcesso()
    {
        $filiais = Filiais::orderBy('nome')->get();
        $usuarios = Usuarios::orderBy('nome')->get();

        return view('reports.permissoes_acesso.create')
        ->with('filiais', $filiais)
        ->with('usuarios', $usuarios);
    }

    public function generatePermissoesAcesso(Request $request)
    {
        $data = $request->except('_token');

        $permissoes = VwPermissoesDeAcesso::where(function ($query) use ($data) {

            if (isset($data['filiais'])) {
                $query->whereIn('filial_id', $data['filiais']);
            }

            if (isset($data['usuarios'])) {
                $query->whereIn('usuario_id', $data['usuarios']);
            }

        })
        ->whereNotNull('opcao_menu')
        ->orderBy('filial')
        ->orderBy('usuario')
        ->get();

        $filiais = [];

        foreach ($permissoes as $permissao) {

            $usuario = $permissao->usuario . ' (' . $permissao->login . '), ' . $permissao->situacao;

            $filiais[$permissao->filial][$usuario][$permissao->grupo][$permissao->modulo][$permissao->opcao_menu] = [
                'visualizar' => $permissao->visualizar,
                'criar'      => $permissao->criar,
                'alterar'    => $permissao->alterar,
                'remover'    => $permissao->remover,
            ];

        }

        $document = new PermissoesAcessoReport;
        
        $document->MakeDocument([$filiais]);
        
        $fileName = 'permissoes_acesso_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
