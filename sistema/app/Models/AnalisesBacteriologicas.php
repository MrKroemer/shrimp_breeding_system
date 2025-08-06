<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalisesBacteriologicas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'analises_bacteriologicas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'amarela_opaca_mm1',
        'amarela_opaca_mm2',
        'amarela_opaca_mm3',
        'amarela_opaca_mm4',
        'amarela_opaca_mm5',
        'amarela_esverdeada',
        'verde',
        'azul',
        'translucida',
        'preta',
        'observacoes',
        'analise_laboratorial_id',
        'tanque_id',
    ];

    public function analise_laboratorial()
    {
        return $this->belongsTo(AnalisesLaboratoriais::class, 'analise_laboratorial_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['tanque_id'])) {
                $query->where('tanque_id', $data['tanque_id']);
            }

            if (isset($data['analise_laboratorial_id'])) {
                $query->where('analise_laboratorial_id', $data['analise_laboratorial_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['analise_laboratorial_id'])) {
                $query->where('analise_laboratorial_id', $data['analise_laboratorial_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
