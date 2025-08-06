<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresuntivasHepatopancreas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'presuntivas_hepatopancreas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'presuntiva_id',
        'lipideos1',
        'lipideos2',
        'lipideos3',
        'lipideos4',
        'lipideos5',
        'lipideos6',
        'lipideos7',
        'lipideos8',
        'lipideos9',
        'lipideos10',
        'qualidade_lipideos',
        'danos_tubulos1',
        'danos_tubulos2',
        'danos_tubulos3',
        'danos_tubulos4',
        'danos_tubulos5',
        'danos_tubulos6',
        'danos_tubulos7',
        'danos_tubulos8',
        'danos_tubulos9',
        'danos_tubulos10',
        'total_coloracao_associada',
        'aditivo',
    ];
    
    public function danos_tubulos()
    {
        $danos_tubulos = array(0,0,0,0,0);
        if(isset($this->danos_tubulos1)){
            switch ($this->danos_tubulos1){
                case null:

                case 0:
                    $danos_tubulos[0]++;
                    break;
                case 1:
                    $danos_tubulos[1]++;
                    break;
                case 2:
                    $danos_tubulos[2]++;
                    break;
                case 3:
                    $danos_tubulos[3]++;
                    break;
                case 4:
                    $danos_tubulos[4]++;
                    break;
                default:
                    $danos_tubulos[0] += 0;
                    $danos_tubulos[1] += 0;
                    $danos_tubulos[2] += 0;
                    $danos_tubulos[3] += 0;
                    $danos_tubulos[4] += 0;

            }
        }
        if(isset($this->danos_tubulos2)){
            switch ($this->danos_tubulos2){
                case 0:
                    $danos_tubulos[0]++;
                    break;
                case 1:
                    $danos_tubulos[1]++;
                    break;
                case 2:
                    $danos_tubulos[2]++;
                    break;
                case 3:
                    $danos_tubulos[3]++;
                    break;
                case 4:
                    $danos_tubulos[4]++;
                    break;
                default:
                    $danos_tubulos[0] += 0;
                    $danos_tubulos[1] += 0;
                    $danos_tubulos[2] += 0;
                    $danos_tubulos[3] += 0;
                    $danos_tubulos[4] += 0;
            }
        }    
        if(isset($this->danos_tubulos3)){
            switch ($this->danos_tubulos3){
                case 0:
                    $danos_tubulos[0]++;
                    break;
                case 1:
                    $danos_tubulos[1]++;
                    break;
                case 2:
                    $danos_tubulos[2]++;
                    break;
                case 3:
                    $danos_tubulos[3]++;
                    break;
                case 4:
                    $danos_tubulos[4]++;
                    break;
                default:
                    $danos_tubulos[0] += 0;
                    $danos_tubulos[1] += 0;
                    $danos_tubulos[2] += 0;
                    $danos_tubulos[3] += 0;
                    $danos_tubulos[4] += 0;                
            }
        }    
        if(isset($this->danos_tubulos4)){
            switch ($this->danos_tubulos4){
                case 0:
                    $danos_tubulos[0]++;
                    break;
                case 1:
                    $danos_tubulos[1]++;
                    break;
                case 2:
                    $danos_tubulos[2]++;
                    break;
                case 3:
                    $danos_tubulos[3]++;
                    break;
                case 4:
                    $danos_tubulos[4]++;
                    break;
                default:
                    $danos_tubulos[0] += 0;
                    $danos_tubulos[1] += 0;
                    $danos_tubulos[2] += 0;
                    $danos_tubulos[3] += 0;
                    $danos_tubulos[4] += 0;
            }
        }    
        if(isset($this->danos_tubulos5)){
            switch ($this->danos_tubulos5){
                case 0:
                    $danos_tubulos[0]++;
                    break;
                case 1:
                    $danos_tubulos[1]++;
                    break;
                case 2:
                    $danos_tubulos[2]++;
                    break;
                case 3:
                    $danos_tubulos[3]++;
                    break;
                case 4:
                    $danos_tubulos[4]++;
                    break;
                default:
                    $danos_tubulos[0] += 0;
                    $danos_tubulos[1] += 0;
                    $danos_tubulos[2] += 0;
                    $danos_tubulos[3] += 0;
                    $danos_tubulos[4] += 0;
            }
        }    
        if(isset($this->danos_tubulos6)){
            switch ($this->danos_tubulos6){
                case 0:
                    $danos_tubulos[0]++;
                    break;
                case 1:
                    $danos_tubulos[1]++;
                    break;
                case 2:
                    $danos_tubulos[2]++;
                    break;
                case 3:
                    $danos_tubulos[3]++;
                    break;
                case 4:
                    $danos_tubulos[4]++;
                    break;
                default:
                    $danos_tubulos[0] += 0;
                    $danos_tubulos[1] += 0;
                    $danos_tubulos[2] += 0;
                    $danos_tubulos[3] += 0;
                    $danos_tubulos[4] += 0;
            }
        }    
        if(isset($this->danos_tubulos7)){
            switch ($this->danos_tubulos7){
                case 0:
                    $danos_tubulos[0]++;
                    break;
                case 1:
                    $danos_tubulos[1]++;
                    break;
                case 2:
                    $danos_tubulos[2]++;
                    break;
                case 3:
                    $danos_tubulos[3]++;
                    break;
                case 4:
                    $danos_tubulos[4]++;
                    break;
                default:
                    $danos_tubulos[0] += 0;
                    $danos_tubulos[1] += 0;
                    $danos_tubulos[2] += 0;
                    $danos_tubulos[3] += 0;
                    $danos_tubulos[4] += 0;
            }
        }    
        if(isset($this->danos_tubulos8)){
            switch ($this->danos_tubulos8){
                case 0:
                    $danos_tubulos[0]++;
                    break;
                case 1:
                    $danos_tubulos[1]++;
                    break;
                case 2:
                    $danos_tubulos[2]++;
                    break;
                case 3:
                    $danos_tubulos[3]++;
                    break;
                case 4:
                    $danos_tubulos[4]++;
                    break;
                default:
                    $danos_tubulos[0] += 0;
                    $danos_tubulos[1] += 0;
                    $danos_tubulos[2] += 0;
                    $danos_tubulos[3] += 0;
                    $danos_tubulos[4] += 0;
            }
        }    
        if(isset($this->danos_tubulos9)){
            switch ($this->danos_tubulos9){
                case 0:
                    $danos_tubulos[0]++;
                    break;
                case 1:
                    $danos_tubulos[1]++;
                    break;
                case 2:
                    $danos_tubulos[2]++;
                    break;
                case 3:
                    $danos_tubulos[3]++;
                    break;
                case 4:
                    $danos_tubulos[4]++;
                    break;
                default:
                    $danos_tubulos[0] += 0;
                    $danos_tubulos[1] += 0;
                    $danos_tubulos[2] += 0;
                    $danos_tubulos[3] += 0;
                    $danos_tubulos[4] += 0;
            }
        }    
        if(isset($this->danos_tubulos10)){
            switch ($this->danos_tubulos10){
                case 0:
                    $danos_tubulos[0]++;
                    break;
                case 1:
                    $danos_tubulos[1]++;
                    break;
                case 2:
                    $danos_tubulos[2]++;
                    break;
                case 3:
                    $danos_tubulos[3]++;
                    break;
                case 4:
                    $danos_tubulos[4]++;
                    break;
                default:
                    $danos_tubulos[0] += 0;
                    $danos_tubulos[1] += 0;
                    $danos_tubulos[2] += 0;
                    $danos_tubulos[3] += 0;
                    $danos_tubulos[4] += 0;
           } 
        }
        if(isset($danos_tubulos[0])){
            $danos_tubulos[0] =  $danos_tubulos[0]*10;
        }else{
            unset($danos_tubulos[0]);
        }
        if(isset($danos_tubulos[1])){
            $danos_tubulos[1] =  $danos_tubulos[1]*10;
        }else{
            unset($danos_tubulos[1]);
        }
        if(isset($danos_tubulos[2])){
            $danos_tubulos[2] =  $danos_tubulos[2]*10;
        }else{
            unset($danos_tubulos[2]);
        }
        if(isset($danos_tubulos[3])){
            $danos_tubulos[3] =  $danos_tubulos[3]*10;
        }else{
            unset($danos_tubulos[3]);
        }
        if(isset($danos_tubulos[4])){
            $danos_tubulos[4] =  $danos_tubulos[4]*10;
        }else{
            unset($danos_tubulos[4]);
        }
        return $danos_tubulos;
    }

    public function media()
    {

        $total = 0;
        $cont = 0;
        $media = 0;

        if(isset($this->lipideos1)){
            $total += $this->lipideos1;
            $cont++;
        }
        if(isset($this->lipideos2)){
            $total += $this->lipideos2;
            $cont++;
        }
        if(isset($this->lipideos3)){
            $total += $this->lipideos3;
            $cont++;
        }
        if(isset($this->lipideos4)){
            $total += $this->lipideos4;
            $cont++;
        }
        if(isset($this->lipideos5)){
            $total += $this->lipideos5;
            $cont++;
        }
        if(isset($this->lipideos6)){
            $total += $this->lipideos6;
            $cont++;
        }
        if(isset($this->lipideos7)){
            $total += $this->lipideos7;
            $cont++;
        }
        if(isset($this->lipideos8)){
            $total += $this->lipideos8;
            $cont++;
        }
        if(isset($this->lipideos9)){
            $total += $this->lipideos9;
            $cont++;
        }
        if(isset($this->lipideos10)){
            $total += $this->lipideos10;
            $cont++;
        }
        
        if($cont > 0){
            $media = $total/$cont;
        }

        return $media;

    }

    public function presuntiva()
    {
        return $this->belongsTo(Presuntiva::class, 'presuntiva_id', 'id');
    }
}
