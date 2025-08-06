<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cidades;
use App\Models\Estados; 
use App\Models\Paises;
use App\Http\Resources\EstadosJsonResource;

class EstadosController extends Controller
{
    public function getJsonEstados(int $pais_id)
    {
        if (is_numeric($pais_id) && $pais_id > 0) {
            return EstadosJsonResource::collection(Estados::where('pais_id', $pais_id)->orderBy('nome', 'asc')->get(['id', 'nome']));
        }

        return EstadosJsonResource::collection(Estados::all(['id', 'nome']));
    }
}
