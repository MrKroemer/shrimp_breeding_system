<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutosTiposCreateFormRequest;
use App\Models\ProdutosTipos;

class ProdutosTiposController extends Controller
{
    public function storeProdutosTipos(ProdutosTiposCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data['nome'] = trim($data['tipo_nome']);

        $insert = ProdutosTipos::create($data);

        if ($insert) {
            return redirect()->back()
            ->with('success', 'Tipo de produto cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o tipo de produto. Tente novamente!');
    }
}
