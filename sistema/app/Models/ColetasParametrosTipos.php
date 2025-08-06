<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColetasParametrosTipos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'coletas_parametros_tipos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sigla',
        'descricao',
        'minimo',
        'maximo',
        'minimoy',
        'maximoy',
        'cor',
        'ativo',
        'alerta',
        'intervalo',
        'unidade_medida_id',
    ];

    public function unidade_medida()
    {
        return $this->belongsTo(UnidadesMedidas::class, 'unidade_medida_id', 'id');
    }

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
