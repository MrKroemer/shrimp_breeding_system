<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwColetasParametrosPorTanque extends Model
{
    protected $table = 'vw_coletas_parametros_por_tanque';
    protected $primaryKey = 'coletas_parametros_id';

    public function valor()
    {
        if (is_null($this->cpa_valor_fatorado)) {
            return $this->cpa_valor;
        }

        return $this->cpa_valor_fatorado;
    }

    public function data_coleta(String $format = 'd/m/Y H:i')
    {
        if (! $this->data_coleta) {
            return $this->data_coleta;
        }

        return Carbon::parse($this->data_coleta)->format($format);
    }
}
