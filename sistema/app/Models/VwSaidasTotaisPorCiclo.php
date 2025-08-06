<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwSaidasTotaisPorCiclo extends Model
{
    protected $table = 'vw_saidas_totais_por_ciclo';

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }

    public function quantidade()
    {
        return round((float) $this->quantidade, 4);
    }

    public function valor_total()
    {
        return round((float) $this->valor_total, 4);
    }

    public function valor_unitario()
    {
        $valor_unitario = 0;
        
        if ($this->quantidade > 0) {
            $valor_unitario = ($this->valor_total / $this->quantidade);
        }

        return round((float) $valor_unitario, 4);
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['produto_id'])) {
                $query->where('produto_id', $data['produto_id']);
            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

            if (isset($data['data_inicial']) && isset($data['data_final'])) {
                $query->whereBetween('data_movimento', [$data['data_inicial'], $data['data_final']]);
            } elseif (isset($data['data_inicial'])) {
                $query->where('data_movimento', '>=', $data['data_inicial']);
            } elseif (isset($data['data_final'])) {
                $query->where('data_movimento', '<=', $data['data_final']);
            }

        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }
}
