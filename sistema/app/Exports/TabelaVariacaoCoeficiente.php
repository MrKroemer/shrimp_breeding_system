<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Tanques;

class TabelaVariacaoCoeficiente implements FromView
{
    public function view(): View
    {   
        $tanques = Tanques::orderBy('sigla', 'desc')
        ->get();

        $ciclos = [];
        foreach($tanques as $tanque) {

            $ciclo = $tanque->ciclos->where('situacao', 6)->sortByDesc('id')->first();

            if(! is_null($ciclo)){

                $ciclos[$tanque->sigla] = $ciclo;

            }

        }

        $analises = [];

        foreach($ciclos as $indice => $ciclo) {

            $analises[$indice] = $ciclo->analises_biometricas->where('situacao', 'S')->sortBy('id');

        }

        return view('admin.analises_biometricas.amostras.biometria', [
            'ciclos'   => $ciclos,
            'analises' => $analises,
        ]);
    }
    
}
