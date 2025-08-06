<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwCiclosPorSetor extends Model
{
    protected $table = 'vw_ciclos_por_setor';
    protected $fillable = [
        'ciclo_id',
        'ciclo_numero',
        'ciclo_situacao',
        'ciclo_inicio',
        'ciclo_fim',
        'ciclo_tipo',
        'tanque_id',
        'tanque_sigla',
        'tanque_situacao',
        'tanque_tipo',
        'tanque_area',
        'filial_id',
        'setor_id',
    ];

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function ciclo_inicio(String $format = 'd/m/Y')
    {
        if (! $this->ciclo_inicio) {
            return $this->ciclo_inicio;
        }

        return Carbon::parse($this->ciclo_inicio)->format($format);
    }

    public function ciclo_fim(String $format = 'd/m/Y')
    {
        if (! $this->ciclo_fim) {
            return $this->ciclo_fim;
        }

        return Carbon::parse($this->ciclo_fim)->format($format);
    }

    public function consumosRecebidos(string $data_inicial, string $data_final)
    {
        return $this->ciclo->consumosRecebidos($data_inicial, $data_final);
    }
}
