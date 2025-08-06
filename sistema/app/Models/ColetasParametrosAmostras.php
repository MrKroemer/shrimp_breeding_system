<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColetasParametrosAmostras extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'coletas_parametros_amostras';
    protected $primaryKey = 'id';
    protected $fillable = [
        'valor',
        'medicao',
        'coletas_parametros_tipos_id',
        'coletas_parametros_id',
        'usuario_id',
        'setor_id',
        'tanque_id'

    ];

    
    public function coletas_parametros_tipos()
    {
        return $this->belongsTo(ColetasParametrosTipos::class, 'coletas_parametros_tipos_id', 'id');
    }

    public function coletas_parametros()
    {
        return $this->belongsTo(ColetasParametros::class, 'coletas_parametros_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function setor()
    {
        return $this->belongsTo(Setores::class, 'setor_id', 'id');
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

            if (isset($data['setor_id'])) {
                $query->where('setor_id', $data['setor_id']);
            }

            if (isset($data['tanque_id'])) {
                $query->where('tanque_id', $data['tanque_id']);
            }

            if (isset($data['coletas_parametros_tipo_id'])) {
                $query->where('coletas_parametros_tipo_id', $data['coletas_parametros_tipo_id']);
            }

            if (isset($data['coletas_parametros_id'])) {
                $query->where('coletas_parametros_id', $data['coletas_parametros_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['coletas_parametros_tipo_id'])) {
                $query->where('coletas_parametros_tipo_id', $data['parametro_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
