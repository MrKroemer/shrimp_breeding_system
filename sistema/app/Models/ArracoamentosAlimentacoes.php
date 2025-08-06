<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArracoamentosAlimentacoes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'arracoamentos_alimentacoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'porcentagem',
        'alimentacao_inicial',
        'alimentacao_final',
        'arracoamento_esquema_id',
    ];

    public function porcentagem_alimentacoes()
    {
        return round(($this->porcentagem / (($this->alimentacao_final - $this->alimentacao_inicial) + 1)), 2);
    }

    public function quantidade_alimentacoes()
    {
        return (($this->alimentacao_final - $this->alimentacao_inicial) + 1);
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['alimentacao_inicial'])) {
                $query->where('alimentacao_inicial', '=', $data['alimentacao_inicial']);
            }

            if (isset($data['alimentacao_final'])) {
                $query->where('alimentacao_final', '=', $data['alimentacao_final']);
            }

        })
        ->orderBy('alimentacao_inicial')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['arracoamento_esquema_id'])) {
                $query->where('arracoamento_esquema_id', $data['arracoamento_esquema_id']);
            }

        })
        ->orderBy('alimentacao_inicial')
        ->paginate($rowsPerPage);
    }
}
