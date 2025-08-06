<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwGruposRateiosTanques extends Model
{
    protected $table = 'vw_grupos_rateios_tanques';

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function setor()
    {
        return $this->belongsTo(Setores::class, 'tanque_setor_id', 'id');
    }

    public function tanque_tipo()
    {
        return $this->belongsTo(TanquesTipos::class, 'tanque_tipo_id', 'id');
    }

    public function grupo_rateio()
    {
        return $this->belongsTo(GruposRateios::class, 'grupo_rateio_id', 'id');
    }
}
