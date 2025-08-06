<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientesFornecedoresCreateFormRequest;
use App\Http\Requests\ClientesFornecedoresEditFormRequest;
use App\Models\ClientesFornecedores;
use App\Models\Paises;
use App\Models\Estados;
use App\Models\Cidades;

class ClientesFornecedoresController extends Controller
{
    private $rowsPerPage = 10;

    public function listingClientesFornecedores()
    {
        $clientes_fornecedores = ClientesFornecedores::listing($this->rowsPerPage);

        return view('admin.clientes_fornecedores.list')
        ->with('clientes_fornecedores', $clientes_fornecedores);
    }
    
    public function searchClientesFornecedores(Request $request)
    {
        $formData = $request->except(['_token']);

        $clientes_fornecedores = ClientesFornecedores::search($formData, $this->rowsPerPage);

        return view('admin.clientes_fornecedores.list')
        ->with('formData',   $formData)
        ->with('clientes_fornecedores', $clientes_fornecedores);
    }

    public function createClientesFornecedores()
    {
		$paises  = Paises::all();

        return view('admin.clientes_fornecedores.create')
		->with('paises', $paises);
    }

    public function storeClientesFornecedores(ClientesFornecedoresCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data['nome']       = trim($data['nome']);
        $data['razao']      = trim($data['razao']);
        $data['cnpj']       = trim($data['cnpj']);
        $data['ie']         = trim($data['ie']);
        $data['cep']        = trim($data['cep']);
        $data['logradouro'] = trim($data['logradouro']);
        $data['numero']     = trim($data['numero']);
        $data['bairro']     = trim($data['bairro']);
        $data['telefone']   = trim($data['telefone']);
        $data['cidade_id']  = trim($data['cidade_id']);
        $data['estado_id']  = trim($data['estado_id']);
        $data['pais_id']    = trim($data['pais_id']);

        $tipo = '';

        if (isset($data['tipo_cliente']) && $data['tipo_cliente'] == 'on') {
            $tipo .= empty($tipo) ? 'C' : '|C';
        }
        if (isset($data['tipo_fornecedor']) && $data['tipo_fornecedor'] == 'on') {
            $tipo .= empty($tipo) ? 'F' : '|F';
        }

        if (empty($tipo)) {

            return redirect()->back()
            ->with('nome',       $data['nome'])
            ->with('razao',      $data['razao'])
            ->with('cnpj',       $data['cnpj'])
            ->with('ie',         $data['ie'])
            ->with('cep',        $data['cep'])
            ->with('logradouro', $data['logradouro'])
            ->with('numero',     $data['numero'])
            ->with('bairro',     $data['bairro'])
            ->with('telefone',   $data['telefone'])
            ->with('pais_id',    $data['pais_id'])
            ->with('warning', 'O "Tipo" cliente e/ou fornecedor deve ser informado!');

        }

        unset($data['tipo_cliente'], $data['tipo_fornecedor']);
        
        $data['tipo'] = $tipo;

        $insert = ClientesFornecedores::create($data);

        if ($insert) {
            return redirect()->route('admin.clientes_fornecedores')
            ->with('success', "Registro cadastrado com sucesso!");
        }

        return redirect()->back()
        ->with('nome',       $data['nome'])
        ->with('razao',      $data['razao'])
        ->with('cnpj',       $data['cnpj'])
        ->with('ie',         $data['ie'])
        ->with('cep',        $data['cep'])
        ->with('logradouro', $data['logradouro'])
		->with('numero',     $data['numero'])
		->with('bairro',     $data['bairro'])
        ->with('telefone',   $data['telefone'])
        ->with('pais_id',    $data['pais_id'])
        ->with('error', "Oops! Algo de errado ocorreu ao salvar o registro. Tente novamente!");
    }

    public function editClientesFornecedores(int $id)
    {
        $data = ClientesFornecedores::find($id);

		$paises    = Paises::all();
        $estados   = Estados::where('pais_id', $data['pais_id'])->get();
        $cidades   = Cidades::where('estado_id', $data['estado_id'])->get();

        return view('admin.clientes_fornecedores.edit')
        ->with('id',         $data['id'])
        ->with('nome',       $data['nome'])
        ->with('razao',      $data['razao'])
        ->with('cnpj',       $data['cnpj'])
        ->with('ie',         $data['ie'])
        ->with('cep',        $data['cep'])
        ->with('logradouro', $data['logradouro'])
		->with('numero',     $data['numero'])
		->with('bairro',     $data['bairro'])
		->with('telefone',   $data['telefone'])
		->with('tipo',       $data['tipo'])
		->with('pais_id',    $data['pais_id'])
        ->with('estado_id',  $data['estado_id'])
		->with('cidade_id',  $data['cidade_id'])
		->with('paises',     $paises)
        ->with('estados',    $estados)
        ->with('cidades',    $cidades);
    }

    public function updateClientesFornecedores(ClientesFornecedoresEditFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data['nome']       = trim($data['nome']);
        $data['razao']      = trim($data['razao']);
        $data['cnpj']       = trim($data['cnpj']);
        $data['ie']         = trim($data['ie']);
        $data['cep']        = trim($data['cep']);
        $data['logradouro'] = trim($data['logradouro']);
        $data['numero']     = trim($data['numero']);
        $data['bairro']     = trim($data['bairro']);
        $data['telefone']   = trim($data['telefone']);
        $data['cidade_id']  = trim($data['cidade_id']);
        $data['estado_id']  = trim($data['estado_id']);
        $data['pais_id']    = trim($data['pais_id']);

        $tipo = '';

        if (isset($data['tipo_cliente']) && $data['tipo_cliente'] == 'on') {
            $tipo .= empty($tipo) ? 'C' : '|C';
        }
        if (isset($data['tipo_fornecedor']) && $data['tipo_fornecedor'] == 'on') {
            $tipo .= empty($tipo) ? 'F' : '|F';
        }

        if (empty($tipo)) {

            return redirect()->back()
            ->with('id', $id)
            ->with('warning', 'O "Tipo" cliente e/ou fornecedor deve ser informado!');

        }

        unset($data['tipo_cliente'], $data['tipo_fornecedor']);
        
        $data['tipo'] = $tipo;

        $update = ClientesFornecedores::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.clientes_fornecedores')
            ->with('success', "Registro atualizada com sucesso!");
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o registro. Tente novamente!');
    }

    public function removeClientesFornecedores(int $id)
    {
        $data = ClientesFornecedores::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.clientes_fornecedores')
            ->with('success', "Registro excluído com sucesso!");
        } 
        
        return redirect()->route('admin.clientes_fornecedores')
        ->with('error', "Ocorreu um erro ao excluir o registro. Tente novamente!");
    }

}
