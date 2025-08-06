<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwPoslarvasEstocadas extends Model
{
    protected $table = 'vw_poslarvas_estocadas';
    protected $primaryKey = 'estoque_entrada_id';

    public function estoque_entrada()
    {
        return $this->belongsTo(EstoqueEntradas::class, 'estoque_entrada_id', 'id');
    }

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('estoque_entrada_id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('estoque_entrada_id', 'desc')
        ->paginate($rowsPerPage);
    }
}
