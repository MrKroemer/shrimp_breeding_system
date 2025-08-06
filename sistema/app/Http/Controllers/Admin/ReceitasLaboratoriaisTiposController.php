<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReceitasLaboratoriaisTiposCreateFormRequest;
use App\Models\ReceitasLaboratoriaisTipos;

class ReceitasLaboratoriaisTiposController extends Controller
{
    public function storeReceitasLaboratoriaisTipos(ReceitasLaboratoriaisTiposCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data['nome'] = mb_strtoupper(trim($data['tipo_nome']));

        $insert = ReceitasLaboratoriaisTipos::create($data);

        if ($insert) {
            return redirect()->back()
            ->with('success', 'Tipo de receita cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o tipo de receita. Tente novamente!');
    }
}
