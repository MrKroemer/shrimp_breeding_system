<?php

namespace App\Http\Controllers;

use App\Models\AnalisesBiometricas;
use App\Models\AnalisesBiometricasAmostras;
use App\Models\Ciclos;
use App\Models\VwAnalisesBiometricas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalisesBiometricasAmostrasController extends Controller{

    private $rowPerPage = 10;
    
    public function getAmostras(){

            //Lista todas as analises por filial.
            $analises_biometricas = VwAnalisesBiometricas::listing($this->rowsPerPage,
            [
                'filial_id' => session('_filial')->id
            ]);

           //Lista todos os ciclos que estão ativos dentro do sistema. 
            $ciclos = Ciclos::where('filial_id', session('_filial')->id)
                            ->orderBy('numero', 'desc')
                            ->get();

            //Traz a consulta do SQL para dentro da variável analiseDB                
            $analiseID = [];
            
            //Obtém amostras aparttir da consulta direta com o DB.
            $amostras = DB::select('select from analises_biometricas_amostras', []);

            $analise_bimetricaId = AnalisesBiometricas::select($amostras) 
                                                      ->where('analise_biometrica_id', $analiseID)
                                                      ->orderBy('analise_biometrica_id', 'desc');
                                                      
            //Retorna todos os valores das amostras por iD;        
            return view('amostras', ['amostras' => $amostras]); 
    }





}