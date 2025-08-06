<?php

namespace App\Models;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class VwBacteriologicasPorTanque extends Model
{
    protected $table = 'vw_bacteriologicas_por_tanque';
    protected $primaryKey = 'analise_bacteriologica_id';

    public function data_analise(String $format = 'd/m/Y')
    {
        if (! $this->data_analise) {
            return $this->data_analise;
        }

        return Carbon::parse($this->data_analise)->format($format);
    }

    public function id()
    {
        return $this->tanque_id;
    }

    public function sigla()
    {
        return $this->tanque_sigla;
    }

    public function amarela_opaca_mm1()  {

        if($this->amarela_opaca_mm1){
            if($this->amarela_opaca_mm1 > 0){
                
                $valor = $this->amarela_opaca_mm1;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            }else if($this->amarela_opaca_mm1 == 0){
                return $this->amarela_opaca_mm1;

            }else{
                return "Incontáveis";
            }
        }else{
                return 0;
        }
    }

    public function amarela_opaca_mm2()  {

        if($this->amarela_opaca_mm2){
            if($this->amarela_opaca_mm2 > 0){
                
                $valor = (string) $this->amarela_opaca_mm2;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            }else if($this->amarela_opaca_mm2 == 0){
                return $this->amarela_opaca_mm2;

            }else{
                return "Incontáveis";
            }
        }else{
                return 0;
        }
    }

    public function amarela_opaca_mm3()  {

        if($this->amarela_opaca_mm3){
            if($this->amarela_opaca_mm3 > 0){
                
                $valor = (string) $this->amarela_opaca_mm3;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            }else if($this->amarela_opaca_mm3 == 0){
                return $this->amarela_opaca_mm3;

            }else{
                return "Incontáveis";
            }
        }else{
                return 0;
        }
    }

    public function amarela_opaca_mm4()  {

        if($this->amarela_opaca_mm4){
            if($this->amarela_opaca_mm4 > 0){
                
                $valor = (string) $this->amarela_opaca_mm4;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            }else if($this->amarela_opaca_mm4 == 0){
                return $this->amarela_opaca_mm4;

            }else{
                return "Incontáveis";
            }
        }else{
                return 0;
        }
    }

    public function amarela_opaca_mm5()  {

        if($this->amarela_opaca_mm5){
            if($this->amarela_opaca_mm5 > 0){
                
                $valor = (string) $this->amarela_opaca_mm5;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            }else if($this->amarela_opaca_mm5 == 0){
                return $this->amarela_opaca_mm5;

            }else{
                return "Incontáveis";
            }
        }else{
                return 0;
        }
    }

    public function amarela_soma()  {

        if(($this->amarela_opaca_mm1 != -1) && ($this->amarela_opaca_mm2 != -1) && ($this->amarela_opaca_mm3 != -1) && ($this->amarela_opaca_mm4 != -1) && ($this->amarela_opaca_mm5 != -1)){
                
                $valor = $this->amarela_opaca_mm1 + $this->amarela_opaca_mm2 + $this->amarela_opaca_mm3 + $this->amarela_opaca_mm4 + $this->amarela_opaca_mm5;

                $valor = (string) $valor;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            
        }else{

            return "Incontáveis";

        }
    }

    public function amarela_soma_hepato()  {

        if(($this->amarela_opaca_mm1 != -1) && ($this->amarela_opaca_mm2 != -1) && ($this->amarela_opaca_mm3 != -1) && ($this->amarela_opaca_mm4 != -1) && ($this->amarela_opaca_mm5 != -1)){
                
                $valor = $this->amarela_opaca_mm1 + $this->amarela_opaca_mm2 + $this->amarela_opaca_mm3 + $this->amarela_opaca_mm4 + $this->amarela_opaca_mm5;

                return $valor;
            
        }else{

            return "Incontáveis";

        }
    }

    public function amarela_esverdeada()  {

        if($this->amarela_esverdeada){
            if($this->amarela_esverdeada > 0){
                
                $valor = (string) $this->amarela_esverdeada;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            }else if($this->amarela_esverdeada == 0){
                return $this->amarela_esverdeada;

            }else{
                return "Incontáveis";
            }
        }else{
                return 0;
        }
    }

    public function verde()  {

        if($this->verde){
            if($this->verde > 0){
                
                $valor = (string) $this->verde;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            }else if($this->verde == 0){
                return $this->verde;

            }else{
                return "Incontáveis";
            }
        }else{
                return 0;
        }
    }

    public function azul()  {

        if($this->azul){
            if($this->azul > 0){
                
                $valor = (string) $this->azul;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            }else if($this->azul == 0){
                return $this->azul;

            }else{
                return "Incontáveis";
            }
        }else{
                return 0;
        }
    }

    public function preta()  {

        if($this->preta){
            if($this->preta > 0){
                
                $valor = (string) $this->preta;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            }else if($this->preta == 0){
                return $this->preta;

            }else{
                return "Incontáveis";
            }
        }else{
                return 0;
        }
    }

    public function translucida()  {

        if($this->translucida){
            if($this->translucida > 0){
                
                $valor = (string) $this->translucida;

                $tamanho = strlen($valor);

                $base = (int) Str::substr($valor, 0, 2);
                
                $base = $base/10;

                $resultado = $base." x 10^".($tamanho-1);
                
                return $resultado;
            }else if($this->translucida == 0){
                return $this->translucida;

            }else{
                return "Incontáveis";
            }
        }else{
                return 0;
        }
    }
        

}

