<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class VwCalcicasPorTanque extends Model
{
    protected $table = 'vw_calcicas_por_tanque';
    protected $primaryKey = 'analise_calcica_id';

    public function data_analise(String $format = 'd/m/Y')
    {
        if (! $this->data_analise) { 
            return $this->data_analise;
        }

        return Carbon::parse($this->data_analise)->format($format);
    }

    public function id()
    {
        return $this->tanque_id;
    }

    public function sigla()
    {
        return $this->tanque_sigla;
    }
}
