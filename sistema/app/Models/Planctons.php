<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planctons extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'planctons';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ciclo_id',
        'usuario_id',
        'filial_id',
        'data',
        'cloroficeas',
        'cianoficeas',
        'diatomaceas',
        'euglenoficeas',
        'protozoarios_ciliados',
        'nematoides',
        'rotiferos',
        'copepodas',
        'dinoflagelados',
        'outros',
        'outros_tratamentos',
        'observacao',
    ];

    public function data_analise()
    {
        return date('d/m/Y', strtotime($this->data));
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
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

            if (isset($date['data'])) {
                $data['data'] = Carbon::createFromFormat('d/m/Y', $data['data'])->format('Y-m-d');

                $query->where('data', $data['data']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }
        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }
}
