<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReprodutoresAnalises extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'reprodutores_analises';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sigla',
        'descricao',
        'ativo',
        'alerta',
        'intervalo',
    ];


    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['sigla'])) {
                $query->where('sigla', 'LIKE', "%{$data['sigla']}%");
            }

            if (isset($data['descricao'])) {
                $query->where('descricao', 'LIKE', "%{$data['descricao']}%");
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            // 
        })
        ->orderBy('descricao')
        ->paginate($rowsPerPage);
    }
}
