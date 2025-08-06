<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setores;
use App\Models\Ciclos;
use App\Models\Tanques;
use App\Models\AnalisesBiometricas;
use App\Models\AnalisesBiometricasAmostras;

class BiometriaParcialController extends Controller
{
    public function createBiometriaParcial()
    {
        $tanques = Tanques::orderBy('sigla')
        ->get();

        $ciclos = [];
        foreach($tanques as $tanque) {

            $ciclo = $tanque->ciclos->where('situacao', 6)->sortByDesc('id')->first();

            if(! is_null($ciclo)){

                $ciclos[$tanque->sigla] = $ciclo;

            }

        }
        //dd($ciclos);
        $analises = [];

        foreach($ciclos as $indice => $ciclo) {

            $analises[$indice] = $ciclo->analises_biometricas->sortBy('data_analise');

        }
        //dd($ciclos);
        return view('admin.analises_biometricas.amostras.biometria')
        ->with('ciclos', $ciclos)
        ->with('analises', $analises);
    }
}
