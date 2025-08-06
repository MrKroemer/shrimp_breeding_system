<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwCiclosPeixes extends Model
{
    protected $table = 'vw_ciclos_peixes';

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function transferencias_recebidas()
    {
        return $this->hasMany(VwTransferenciasAnimais::class, 'ciclo_destino_id', 'ciclo_id');
    }

    public function transferencias_enviadas()
    {
        return $this->hasMany(VwTransferenciasAnimais::class, 'ciclo_origem_id', 'ciclo_id');
    }

    public function ciclo_inicio(String $format = 'd/m/Y')
    {
        return $this->ciclo->data_inicio($format);
    }

    public function ciclo_fim(String $format = 'd/m/Y')
    {
        return $this->ciclo->data_fim($format);
    }

    public function consumosRecebidos(string $data_inicial, string $data_final)
    {
        return $this->ciclo->consumosRecebidos($data_inicial, $data_final);
    }

    public function qtd_peixes()
    {
        $qtd_peixes = ($this->animais_recebidos - $this->animais_enviados);

        if ($qtd_peixes < 0) {
            return 0;
        }

        return $qtd_peixes;
    }

    public function custo_transferencias()
    {
        $recebidas = $this->transferencias_recebidas->sum('custo_atribuido');
        $enviadas  = $this->transferencias_enviadas->sum('custo_atribuido');

        return ($recebidas - $enviadas);
    }

    public function custo_total()
    {
        $custo_total = $this->ciclo->custo_saidas();
        $custo_total += $this->custo_transferencias();

        return $custo_total;
    }
}
