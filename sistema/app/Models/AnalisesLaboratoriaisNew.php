<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AnalisesLaboratoriaisNew extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'analises_laboratoriais_new';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_analise',
        'analise_laboratorial_tipo_id',
        'tanque_tipo_id',
        'filial_id',
    ];

    public function data_analise(String $format = 'd/m/Y')
    {
        if (! $this->data_analise) {
            return $this->data_analise;
        }

        return Carbon::parse($this->data_analise)->format($format);
    }

    public function analise_laboratorial_tipo()
    {
        return $this->belongsTo(AnalisesLaboratoriaisTipos::class, 'analise_laboratorial_tipo_id', 'id');
    }

    public function bacteriologicas()
    {
        return $this->hasMany(AnalisesBacteriologicasNew::class, 'analise_laboratorial_id', 'id');
    }

    public function calcicas()
    {
        return $this->hasMany(AnalisesCalcicasNew::class, 'analise_laboratorial_id', 'id');
    }

    public function magnesicas()
    {
        return $this->hasMany(AnalisesMagnesicasNew::class, 'analise_laboratorial_id', 'id');
    }

    public function bacteriologicas_por_tanque()
    {
        return $this->hasMany(VwBacteriologicasPorTanque::class, 'analise_laboratorial_id', 'id');
    }

    public function calcicas_por_tanque()
    {
        return $this->hasMany(VwCalcicasPorTanque::class, 'analise_laboratorial_id', 'id');
    }

    public function magnesicas_por_tanque()
    {
        return $this->hasMany(VwMagnesicasPorTanque::class, 'analise_laboratorial_id', 'id');
    }

    public function tanque_tipo()
    {
        return $this->belongsTo(TanquesTipos::class, 'tanque_tipo_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }
            
            if (isset($data['data_analise'])) {

                $data['data_analise'] = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-m-d');

                $query->where('data_analise', '=', $data['data_analise']);

            }

            if (isset($data['tanque_tipo_id'])) {
                $query->where('tanque_tipo_id', $data['tanque_tipo_id']);
            }

            if (isset($data['analise_laboratorial_tipo_id'])) {
                $query->where('analise_laboratorial_tipo_id', $data['analise_laboratorial_tipo_id']);
            }

            if (isset($data['analises_laboratoriais_tipos'])) {
                $query->whereIn('analise_laboratorial_tipo_id', $data['analises_laboratoriais_tipos']);
            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('data_analise', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }
            
            if (isset($data['analises_laboratoriais_tipos'])) {
                $query->whereIn('analise_laboratorial_tipo_id', $data['analises_laboratoriais_tipos']);
            }

            

        })
        ->orderBy('data_analise', 'desc')
        ->paginate($rowsPerPage);
    }
}
