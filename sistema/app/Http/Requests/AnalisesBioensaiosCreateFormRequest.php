<?php 


namespace App\Http\Request;

use Illuminate\Foundation\Requests;
use Illuminate\Http\Request;


class AnalisesBioensaiosCreateFormRequest extends Request{

    public function authorize(){
        
        return true;

    }

    public function rules(){

        return [

            'data_analise' => 'required',
            'ciclo'        =>  'required',
        ];
    } 

    public function message(){

        return [

            'data_analise' => 'O campo de data análise deve ser informado',
            'ciclo'        => 'Você deve selecioanr um ciclo',
        ];
    }


}
