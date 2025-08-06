<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresuntivasTemp extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'presuntivas_temporario';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ciclo_id',                    
        'data',                        
        'dias_cultivo', 
        'data_povoamento',
        'data_analise',
        'densidade',                
        'gramatura',                   
        'cor_animal',                 
        'edema_uropodos',             
        'aparencia_carapaca',          
        'antenas',                    
        'total_cor_animal',            
        'total_antenas',               
        'total_aparencia_carapaca',    
        'total_aparencia_musculatura', 
        'total_coloracao_associada', 
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
        'epistilis',                  
        'zoothamium',                  
        'acinetos',                    
        'necroses',                    
        'melanoses',                   
        'deformidade',                
        'leucotrix',                   
        'biofloco',                   
        'cristais_normal',             
        'cristais1',
        'cristais2',
        'cristais3',
        'cristais4',
        'cristais5',
        'cristais6',
        'cristais7',
        'cristais8',
        'cristais9',
        'cristais10',
        'intestino_racao',             
        'intestino_biofloco',          
        'intestino_algas',             
        'intestino_canibalismo',       
        'hemolinfa',        
        'observacao',                  
    ];
    public function danos_tubulos()
    {
        $danos_tubulos = array(0,0,0,0,0);
        if(isset($this->danos_tubulos1)){
            switch ($this->danos_tubulos1){
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
            }
        }            
        $danos_tubulos[0] =  $danos_tubulos[0]*10;
        $danos_tubulos[1] =  $danos_tubulos[1]*10;
        $danos_tubulos[2] =  $danos_tubulos[2]*10;
        $danos_tubulos[3] =  $danos_tubulos[3]*10;
        $danos_tubulos[4] =  $danos_tubulos[4]*10;
        return $danos_tubulos;
    }
    public function cristais()
    {
        $cristais = array(0,0,0,0,0);
        if(isset($this->cristais1)){
            switch ($this->cristais1){
                case 0:
                    $cristais[0]++;
                    break;
                case 1:
                    $cristais[1]++;
                    break;
                case 2:
                    $cristais[2]++;
                    break;
                case 3:
                    $cristais[3]++;
                    break;
                case 4:
                    $cristais[4]++;
                    break;
    
            }            
        }
        if(isset($this->cristais2)){
            switch ($this->cristais2){
                case 0:
                    $cristais[0]++;
                    break;
                case 1:
                    $cristais[1]++;
                    break;
                case 2:
                    $cristais[2]++;
                    break;
                case 3:
                    $cristais[3]++;
                    break;
                case 4:
                    $cristais[4]++;
                    break;
    
            }            
        }
        if(isset($this->cristais3)){
            switch ($this->cristais3){
                case 0:
                    $cristais[0]++;
                    break;
                case 1:
                    $cristais[1]++;
                    break;
                case 2:
                    $cristais[2]++;
                    break;
                case 3:
                    $cristais[3]++;
                    break;
                case 4:
                    $cristais[4]++;
                    break;
    
            }            
        }
        if(isset($this->cristais4)){
            switch ($this->cristais4){
                case 0:
                    $cristais[0]++;
                    break;
                case 1:
                    $cristais[1]++;
                    break;
                case 2:
                    $cristais[2]++;
                    break;
                case 3:
                    $cristais[3]++;
                    break;
                case 4:
                    $cristais[4]++;
                    break;
    
            }            
        }
        if(isset($this->cristais5)){
            switch ($this->cristais5){
                case 0:
                    $cristais[0]++;
                    break;
                case 1:
                    $cristais[1]++;
                    break;
                case 2:
                    $cristais[2]++;
                    break;
                case 3:
                    $cristais[3]++;
                    break;
                case 4:
                    $cristais[4]++;
                    break;
    
            }            
        }
        if(isset($this->cristais6)){
            switch ($this->cristais6){
                case 0:
                    $cristais[0]++;
                    break;
                case 1:
                    $cristais[1]++;
                    break;
                case 2:
                    $cristais[2]++;
                    break;
                case 3:
                    $cristais[3]++;
                    break;
                case 4:
                    $cristais[4]++;
                    break;
    
            }            
        }
        if(isset($this->cristais7)){
            switch ($this->cristais7){
                case 0:
                    $cristais[0]++;
                    break;
                case 1:
                    $cristais[1]++;
                    break;
                case 2:
                    $cristais[2]++;
                    break;
                case 3:
                    $cristais[3]++;
                    break;
                case 4:
                    $cristais[4]++;
                    break;
    
            }            
        }
        if(isset($this->cristais8)){
            switch ($this->cristais8){
                case 0:
                    $cristais[0]++;
                    break;
                case 1:
                    $cristais[1]++;
                    break;
                case 2:
                    $cristais[2]++;
                    break;
                case 3:
                    $cristais[3]++;
                    break;
                case 4:
                    $cristais[4]++;
                    break;
    
            }            
        }
        if(isset($this->cristais9)){
            switch ($this->cristais9){
                case 0:
                    $cristais[0]++;
                    break;
                case 1:
                    $cristais[1]++;
                    break;
                case 2:
                    $cristais[2]++;
                    break;
                case 3:
                    $cristais[3]++;
                    break;
                case 4:
                    $cristais[4]++;
                    break;
    
            }            
        }
        if(isset($this->cristais10)){
            switch ($this->cristais10){
                case 0:
                    $cristais[0]++;
                    break;
                case 1:
                    $cristais[1]++;
                    break;
                case 2:
                    $cristais[2]++;
                    break;
                case 3:
                    $cristais[3]++;
                    break;
                case 4:
                    $cristais[4]++;
                    break;
     
    }           }
        $cristais[0] = $cristais[0]*10;
        $cristais[1] = $cristais[1]*10;
        $cristais[2] = $cristais[2]*10;
        $cristais[3] = $cristais[3]*10;
        $cristais[4] = $cristais[4]*10;
        return $cristais;
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

    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class, 'ciclo_id', 'id');
    }
}
