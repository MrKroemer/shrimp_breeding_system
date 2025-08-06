<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwTransferenciasAnimais extends Model
{
    protected $table = 'vw_transferencias_animais';

    public function transferencia_animal()
    {
        return $this->belongsTo(TransferenciasAnimais::class, 'transferencia_id', 'id');
    }

    public function ciclo_origem()
    {
        return $this->transferencia_animal->ciclo_origem();
    }

    public function ciclo_destino()
    {
        return $this->transferencia_animal->ciclo_destino();
    }

    public function data_movimento(String $format = 'd/m/Y')
    {
        return $this->transferencia_animal->data_movimento($format);
    }

    public function proporcao()
    {
        return $this->transferencia_animal->proporcao();
    }

    public function situacao($situacao = null)
    {
        return $this->transferencia_animal->situacao($situacao);
    }

    public function custo_atribuido(int $ciclo_id)
    {
        if ($this->ciclo_origem->id == $ciclo_id) {
            return ($this->custo_atribuido * -1);
        }

        return $this->custo_atribuido;
    }
}
