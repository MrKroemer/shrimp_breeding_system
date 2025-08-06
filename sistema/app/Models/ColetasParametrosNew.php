<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ColetasParametrosNew extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'coletas_parametros_new';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_coleta',
        'tanque_tipo_id',
        'coleta_parametro_tipo_id',
        'filial_id',
        'usuario_id',
        'sonda_laboratorial_id',
        'situacao',
    ];

    public function data_coleta(String $format = 'd/m/Y')
    {
        if (! $this->data_coleta) {
            return $this->data_coleta;
        }

        return Carbon::parse($this->data_coleta)->format($format);
    }

    public function tanque_tipo()
    {
        return $this->belongsTo(TanquesTipos::class, 'tanque_tipo_id', 'id');
    }

    public function sonda_laboratorial()
    {
        return $this->belongsTo(SondasLaboratoriais::class, 'sonda_laboratorial_id', 'id');
    }

    public function coleta_parametro_tipo()
    {
        return $this->belongsTo(ColetasParametrosTipos::class, 'coleta_parametro_tipo_id', 'id');
    }

    public function amostras()
    {
        return $this->hasMany(ColetasParametrosAmostrasNew::class, 'coleta_parametro_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['tanque_tipo_id'])) {
                $query->where('tanque_tipo_id', $data['tanque_tipo_id']);
            }

            if (isset($data['coleta_parametro_tipo_id'])) {
                $query->where('coleta_parametro_tipo_id', $data['coleta_parametro_tipo_id']);
            }

            if (isset($data['data_coleta'])) {

                $data['data_coleta'] = Carbon::createFromFormat('d/m/Y', $data['data_coleta'])->format('Y-m-d');

                $query->where('data_coleta', $data['data_coleta']);

            }

        })
        ->orderBy('data_coleta', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('data_coleta', 'desc')
        ->paginate($rowsPerPage);
    }
}
