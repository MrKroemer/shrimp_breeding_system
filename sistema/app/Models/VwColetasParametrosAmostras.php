<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwColetasParametrosAmostras extends Model
{
    protected $table = 'vw_coletas_parametros_amostras';
    protected $primaryKey = 'id';

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function tanque_tipo()
    {
        return $this->belongsTo(TanquesTipos::class, 'tanque_tipo_id', 'id');
    }

    public function coleta_parametro()
    {
        return $this->belongsTo(ColetasParametrosNew::class, 'coleta_parametro_id', 'id');
    }

    public function coleta_parametro_tipo()
    {
        return $this->belongsTo(ColetasParametrosTipos::class, 'coleta_parametro_tipo_id', 'id');
    }
    public function valor()
    {
        if (is_null($this->valor_fatorado)) {
            return $this->valor;
        }

        return $this->valor_fatorado;
    }

    public function data_coleta(String $format = 'd/m/Y')
    {
        if (! $this->data_coleta) {
            return $this->data_coleta;
        }

        return Carbon::parse($this->data_coleta)->format($format);
    }
}
