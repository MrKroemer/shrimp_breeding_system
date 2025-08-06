<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SondasFatores extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'sondas_fatores';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fator',
        'situacao',
        'sonda_laboratorial_id',
        'coleta_parametro_tipo_id',
        'usuario_id',
    ];

    public function sondas_laboratoriais()
    {
        return $this->belongsTo(SondasLaboratoriais::class, 'sonda_laboratorial_id', 'id');
    }

    public function coletas_parametros_tipos()
    {
        return $this->belongsTo(ColetasParametrosTipos::class, 'coleta_parametro_tipo_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
    }


    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['sonda_laboratorial_id'])) {
                $query->where('sonda_laboratorial_id', $data['sonda_laboratorial_id']);
            }

            if (isset($data['coleta_parametro_tipo_id'])) {
                $query->where('coleta_parametro_tipo_id', $data['coleta_parametro_tipo_id']);
            }

        })
        ->orderBy('sonda_laboratorial_id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['sonda_laboratorial_id'])) {
                $query->where('sonda_laboratorial_id', $data['sonda_laboratorial_id']);
            }

        })
        ->orderBy('sonda_laboratorial_id')
        ->paginate($rowsPerPage);
    }
}
