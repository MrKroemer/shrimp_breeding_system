<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cidades;
use App\Models\Estados; 
use App\Models\Paises;
use App\Http\Resources\CidadesJsonResource;

class CidadesController extends Controller
{
    public function getJsonCidades(int $estado_id)
    {
        if (is_numeric($estado_id) && $estado_id > 0) {
            return CidadesJsonResource::collection(Cidades::where('estado_id', $estado_id)->orderBy('nome', 'asc')->get(['id', 'nome']));
        }

        return CidadesJsonResource::collection(Cidades::all(['id', 'nome']));
    }
}
