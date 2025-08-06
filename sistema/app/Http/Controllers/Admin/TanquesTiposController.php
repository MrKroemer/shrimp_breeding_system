<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TanquesTiposCreateFormRequest;
use App\Models\TanquesTipos;

class TanquesTiposController extends Controller
{
    public function storeTanquesTipos(TanquesTiposCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data['nome'] = trim($data['tipo_nome']);

        $insert = TanquesTipos::create($data);

        if ($insert) {
            return redirect()->back()
            ->with('success', 'Tipo de tanque cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o tipo de tanque. Tente novamente!');
    }
}
