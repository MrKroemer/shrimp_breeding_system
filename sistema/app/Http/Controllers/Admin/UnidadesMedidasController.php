<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UnidadesMedidasCreateFormRequest;
use App\Models\UnidadesMedidas;

class UnidadesMedidasController extends Controller
{
    public function storeUnidadesMedidas(UnidadesMedidasCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data['nome']  = trim($data['unidade_nome']);
        $data['sigla'] = trim($data['unidade_sigla']);

        $insert = UnidadesMedidas::create($data);

        if ($insert) {
            return redirect()->back()
            ->with('success', 'Unidade de medida cadastrada com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a unidade de medida. Tente novamente!');
    }
}
