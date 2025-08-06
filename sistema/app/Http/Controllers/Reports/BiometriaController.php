<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwCiclosPorSetor;

class BiometriaController extends Controller
{
    public function createBiometria()
    {
        return view('reports.biometria.create');
    }

    public function generateBiometria()
    {
        
        $ciclos = VwCiclosPorSetor::whereBetween('ciclo_situacao', [2,7])
        ->orderBy('tanque_sigla')
        ->get();

        //dd($ciclos);

        $document = new BiometriaReport;
        
        $document->MakeDocument($ciclos);
        
        $fileName = 'RelatorioBiometria_' . date('d-m-Y');

        return $document->Output($fileName, 'I');
       
        
    }
}
