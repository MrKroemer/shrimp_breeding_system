<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwDespescas extends Model
{
    protected $table = 'vw_despescas';

    public function despesca()
    {
        return $this->belongsTo(Despescas::class, 'id', 'id');
    }

    public function ciclo()
    {
        return $this->despesca->ciclo();
    }

    public function filial()
    {
        return $this->despesca->filial();
    }

    public function data_inicio(String $format = 'd/m/Y H:i')
    {
        return $this->despesca->data_inicio($format);
    }

    public function data_fim(String $format = 'd/m/Y H:i')
    {
        return $this->despesca->data_fim($format);
    }

    public function tipo($tipo = null)
    {
        return $this->despesca->tipo($tipo);
    }

    public function aproveitamento()
    {
        return $this->despesca->aproveitamento();
    }

    public function sucedeDataInicio(String $data)
    {
        return $this->despesca->sucedeDataInicio($data);
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

            if (isset($data['tanque_id'])) {
                $query->where('tanque_id', $data['tanque_id']);
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
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
