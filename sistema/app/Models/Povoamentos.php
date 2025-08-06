<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Povoamentos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'povoamentos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_inicio',
        'data_fim',
        'ciclo_id',
        'filial_id',
        'usuario_id',
    ];

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

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function lotes()
    {
        return $this->hasMany(PovoamentosLotes::class, 'povoamento_id', 'id');
    }

    public function estoque_saida_povoamento()
    {
        return $this->hasMany(EstoqueSaidasPovoamentos::class, 'povoamento_id', 'id');
    }

    public function sucedeDataInicio(String $data)
    {
        $data = Carbon::parse($data)->format('Y-m-d H:i:s');

        return (strtotime($this->data_inicio) <= strtotime($data));
    }

    public function sucedeDataFim(String $data)
    {
        $data = Carbon::parse($data)->format('Y-m-d H:i:s');

        return (strtotime($this->data_fim) <= strtotime($data));
    }

    public function qtd_poslarvas()
    {
        $qtd_poslarvas = 0;

        foreach ($this->lotes as $lote) {
            $qtd_poslarvas += $lote->lote->quantidade;
        }

        return $qtd_poslarvas;
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

            if (isset($data['ciclo_id'])) {
                $query->where('ciclo_id', $data['ciclo_id']);
            }

            if (isset($data['data_inicio'])) {
                $data['data_inicio'] = Carbon::createFromFormat('d/m/Y H:i', $data['data_inicio'])->format('Y-m-d H:i:s');
            }

            if (isset($data['data_fim'])) {
                $data['data_fim'] = Carbon::createFromFormat('d/m/Y H:i', $data['data_fim'])->format('Y-m-d H:i:s');
            }

            if (isset($data['data_inicio']) && isset($data['data_fim'])) {

                $query->where(function ($query) use ($data) {
                    $query->whereBetween('data_inicio', [$data['data_inicio'], $data['data_fim']]);
                    $query->orWhereBetween('data_fim',  [$data['data_inicio'], $data['data_fim']]);
                });

            } elseif (isset($data['data_inicio'])) {

                $query->where('data_inicio', $data['data_inicio']);

            } elseif (isset($data['data_fim'])) {

                $query->where('data_fim', $data['data_fim']);

            }

        })
        ->orderBy('data_inicio', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('data_inicio', 'desc')
        ->paginate($rowsPerPage);
    }
}
