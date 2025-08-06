<?php

 namespace App\Http\Controllers\Charts;


 use App\Http\Controllers\Controller;


class RouteTestGraphicCharts extends Controller
{
        
       public function __construct()
       {
           return $this->middleware('auth');
       }
    
    
        public function routeTest(){
            return '<h2>Rota teste para Crescimento de Amostras</h2>';
        }
}