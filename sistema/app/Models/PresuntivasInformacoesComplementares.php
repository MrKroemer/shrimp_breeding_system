<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresuntivasInformacoesComplementares extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'presuntivas_informacoes_complementares';
    protected $primaryKey = 'id';
    protected $fillable = [
        'presuntiva_id',
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
            }
        }
        $cristais[0] = $cristais[0]*10;
        $cristais[1] = $cristais[1]*10;
        $cristais[2] = $cristais[2]*10;
        $cristais[3] = $cristais[3]*10;
        $cristais[4] = $cristais[4]*10;
        return $cristais;
    }

    public function presuntiva()
    {
        return $this->belongsTo(Presuntivas::class, 'presuntiva_id', 'id');
    }
}
