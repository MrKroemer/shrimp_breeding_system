<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AnalisesBiometricasOld extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'analises_biometricas_old';
    protected $primaryKey = 'id';
    protected $fillable = [
        'classe0to10',
        'classe10to20',
        'classe20to30',
        'classe30to40',
        'classe40to50',
        'classe50to60',
        'classe60to70',
        'classe70to80',
        'classe80to100',
        'classe100to120',
        'classe120to150',
        'classe150toUP',
        'total_animais',
        'sobrevivencia',
        'peso_total',
        'peso_medio',
        'moles',
        'semimoles',
        'flacidos',
        'opacos',
        'deformados',
        'caudas_vermelhas',
        'necroses_nivel1',
        'necroses_nivel2',
        'necroses_nivel3',
        'necroses_nivel4',
        'data_analise',
        'ciclo_id',
        'filial_id',
        'usuario_id',
    ];

    public function data_analise(String $format = 'd/m/Y')
    {
        if (! $this->data_analise) {
            return $this->data_analise;
        }

        return Carbon::parse($this->data_analise)->format($format);
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['data_analise'])) {

                $data['data_analise'] = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-m-d');

                $query->where('data_analise', $data['data_analise']);

            }

            if (isset($data['ciclo_id'])) {
                $query->where('ciclo_id', $data['ciclo_id']);
            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->orderBy('data_analise', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->orderBy('data_analise', 'desc')
        ->paginate($rowsPerPage);
    }
}
