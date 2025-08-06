<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Requests\EspeciesCreateFormRequest;
use App\Models\Especies;

class EspeciesController extends Controller
{
    public function storeEspecies(EspeciesCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data['nome_cientifico'] = mb_strtoupper($data['nome_cientifico']);
        $data['nome_comum']      = mb_strtoupper($data['nome_comum']);
        
        $data = DataPolisher::toPolish($data);
        
        $especie = Especies::create($data);

        if ($especie instanceof Especies) {
            return redirect()->back()
            ->with('success', 'Espécie cadastrada com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a espécie. Tente novamente!');
    }
}
