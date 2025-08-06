<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArracoamentosAplicacoesTipos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'arracoamentos_aplicacoes_tipos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome'
    ];

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['nome'])) {
                $query->where('nome', 'LIKE', "%{$data['nome']}%");
            }

        })
        ->orderBy('nome')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            //
        })
        ->orderBy('nome')
        ->paginate($rowsPerPage);
    }
}
