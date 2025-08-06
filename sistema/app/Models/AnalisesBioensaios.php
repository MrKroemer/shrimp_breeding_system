<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class AnalisesBioensaios extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'modificado_em';


    protected $table =  'analises_bioensaios';
    protected $primary_key = 'id';
    
    protected $filiable = [
        
        'data_analise',
        'povoamento_id',
        'ciclo_id',
        'filial_id',
        'usuario_id',
        'coletado_24h',
        'coletado_48h',
        'coletado_72h',
    ];

    public function data_analise(string $format = 'd/m/Y'){

        if (! $this->data_analise){

            return $this->data_analise;
        }

        return Carbon::parse($this->data_analise)->format($format);
    }

    public function ciclo(){
        
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function povoamento(){

        return $this->belongsTo(Povoamentos::class, 'povoamento_id', 'id');
    }
    
    public function filiais(){

        return $this->belongsTo(Filiais::class, 'filiais_id', 'id');
    }

    public function usuarios(){

        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');

    }



    public static function search(Array $data, int $rowsPerPage){

        return static::where(function ($query) use ($data){
               $query->where('data_analise', $data['data_analise']);

               if(isset($data['data_analise'])){
                   $data['data_analise'] = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-d-m');
                   $query->where('data_analise', $data['data_analise']);
               }

               if(isset($data['ciclo_id'])){

                    $query->where('ciclo_id', $data['ciclo_id']);
               }
        })
                   ->orderBy('data_analise', 'desc')
                   ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null ){
            
            return static::where( function ($query) use ($data){
                           $query->where('filial_id', session('filial_id')->id);
                                 
            })

                                  ->orderBy('data_analise', 'desc')
                                  ->paginate($rowsPerPage);

    }
    
}
