<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ColetasParametros extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'coletas_parametros';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_coleta',
        'medicao',
        'coletas_parametros_tipos_id',
        'usuario_id',
        'setor_id'

    ];
/*
    public function data_coleta()
    {
        return date('d/m/Y', strtotime($this->data_coleta));
    }
*/
    public function data_coleta(String $format = 'd/m/Y')
    {
        if (! $this->data_coleta) {
            return $this->data_coleta;
        }

        return Carbon::parse($this->data_coleta)->format($format);
    }

    public function coletas_parametros_tipos()
    {
        return $this->belongsTo(ColetasParametrosTipos::class, 'coletas_parametros_tipos_id', 'id');
    }

    
    public function coletas_parametros_amostras()
    {
        return $this->hasMany(ColetasParametrosAmostras::class, 'coletas_parametros_id', 'id');
    }

    public function coletas_por_tanque()
    {
        return $this->hasMany(VwColetasParametrosPorTanque::class, 'coletas_parametros_id', 'id');
    }

    public function verifica_amostras()
    {
        return $this->hasMany(VwColetasAmostras::class, 'coleta_id', 'id');
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

            if (isset($data['coletas_parametros_tipos_id'])) {
                $query->where('coletas_parametros_tipos_id', $data['coletas_parametros_tipos_id']);
            }

            if (isset($data['data_coleta'])) {

                $data['data_coleta'] = Carbon::createFromFormat('d/m/Y', $data['data_coleta'])->format('Y-m-d');

                $query->where('data_coleta', '=', $data['data_coleta']);

            }

        })
        ->orderBy('data_coleta', 'desc')
        ->orderBy('medicao', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['coletas_parametros_tipo_id'])) {
                $query->where('coletas_parametros_tipo_id', $data['parametro_id']);
            }

        })
        ->orderBy('setor_id', 'desc')
        ->orderBy('data_coleta', 'desc')
        ->orderBy('medicao', 'desc')
        ->paginate($rowsPerPage);
    }
}
