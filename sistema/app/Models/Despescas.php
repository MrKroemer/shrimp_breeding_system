<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateTime;

class Despescas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'despescas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_inicio',
        'data_fim',
        'qtd_prevista',
        'qtd_despescada',
        'peso_medio',
        'observacoes',
        'ordinal',
        'tipo',
        'ciclo_id',
        'filial_id',
        'usuario_id',
    ];

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function data_inicio(String $format = 'd/m/Y H:i')
    {
        if (! $this->data_inicio) {
            return $this->data_inicio;
        }

        return Carbon::parse($this->data_inicio)->format($format);
    }

    public function data_fim(String $format = 'd/m/Y H:i')
    {
        if (! $this->data_fim) {
            return $this->data_fim;
        }

        return Carbon::parse($this->data_fim)->format($format);
    }

    public function dias_despesca(String $format = 'd/m/Y H:i')
    {   
       
        $firstDay = date($this->data_inicio);
        $lastDay  = date($this->data_fim);

       

        if((! $this->data_fim) || (! $this->data_inicio)){ 

            return ($this->data_fim - $this->data_inicio);
        }
          
        $fishingDays =  (int) floor((strtotime($lastDay) - strtotime($firstDay)) / (60*60*24));

        return $fishingDays;
        
    }

    public function tipo($tipo = null)
    {
        $tipos = [
            1 => 'Despesca completa',
            2 => 'Despesca parcial',
            3 => 'Descarte pré povoamento',
            4 => 'Descarte pós povoamento',
        ];

        if (! $tipo) {
            $tipo = $this->tipo;
        }

        return $tipos[$this->tipo];
    }

    public function aproveitamento()
    {
        if ($this->qtd_prevista == 0) {
            $this->qtd_prevista = 1;
        }

        return ($this->qtd_despescada / $this->qtd_prevista) * 100;
    }

    public function sucedeDataInicio(String $data)
    {
        $data = Carbon::parse($data)->format('Y-m-d H:i:s');

        return (strtotime($this->data_inicio) <= strtotime($data));
    }
}
