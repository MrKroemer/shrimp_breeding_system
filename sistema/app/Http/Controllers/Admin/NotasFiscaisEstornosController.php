<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotasFiscais;
use App\Models\ClientesFornecedores;
use Carbon\Carbon;

class NotasFiscaisEstornosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingNotasFiscaisEstornos()
    {
        $notas_fiscais = NotasFiscais::listing($this->rowsPerPage, [
            'situacao'    => 'E',
            'filial_cnpj' => session('_filial')->cnpj,
        ]);

        return view('admin.notas_fiscais_estornos.list')
        ->with('notas_fiscais', $notas_fiscais);
    }

    public function searchNotasFiscaisEstornos(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['situacao']    = 'E';
        $formData['filial_cnpj'] = session('_filial')->cnpj;

        $notas_fiscais = NotasFiscais::search($formData, $this->rowsPerPage);

        return view('admin.notas_fiscais_estornos.list')
        ->with('formData',      $formData)
        ->with('notas_fiscais', $notas_fiscais);
    }

    public function viewNotasFiscaisEstornos(int $id)
    {
        $data = NotasFiscais::find($id);

        $fornecedores = ClientesFornecedores::where('tipo', 'F')->orWhere('tipo', 'C|F')->get();
        $clientes     = ClientesFornecedores::where('tipo', 'C')->orWhere('tipo', 'C|F')->get();

        $data['data_emissao']   = Carbon::parse($data['data_emissao'])->format('d/m/Y');
        $data['data_movimento'] = Carbon::parse($data['data_movimento'])->format('d/m/Y');

        return view('admin.notas_fiscais_estornos.edit')
        ->with('id',              $id)
        ->with('chave',           $data['chave'])
        ->with('numero',          $data['numero'])
        ->with('data_emissao',    $data['data_emissao'])
        ->with('valor_total',     $data['valor_total'])
        ->with('valor_frete',     $data['valor_frete'])
        ->with('valor_desconto',  $data['valor_desconto'])
        ->with('icms_desonerado', $data['icms_desonerado'])
        ->with('fornecedor_id',   $data['fornecedor_id'])
        ->with('cliente_id',      $data['cliente_id'])
        ->with('data_movimento',  $data['data_movimento'])
        ->with('situacao',        $data['situacao'])
        ->with('fornecedores',    $fornecedores)
        ->with('clientes',        $clientes);
    }
}
