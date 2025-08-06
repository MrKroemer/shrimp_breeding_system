<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArracoamentosEsquemas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'arracoamentos_esquemas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'dia_inicial',
        'dia_final',
        'descricao',
        'periodo',
        'arracoamento_perfil_id',
    ];

    public function racoes()
    {
        return $this->hasMany(ArracoamentosRacoes::class, 'arracoamento_esquema_id', 'id');
    }

    public function alimentacoes()
    {
        return $this->hasMany(ArracoamentosAlimentacoes::class, 'arracoamento_esquema_id', 'id');
    }

    public function porcentagem_racaos()
    {
        $porcentagem = $this->racoes->sum('porcentagem');

        return (100 - $porcentagem);
    }

    public function porcentagem_alimentacoes()
    {
        $porcentagem =  $this->alimentacoes->sum('porcentagem');

        return (100 - $porcentagem);
    }

    public function periodo($periodo = null)
    {
        $periodos = [
            'N'  => 'Normal',
            'T'  => 'Transição',
        ];

        if (! $periodo) {
            return $periodos;
        }

        return $periodos[$periodo];
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['dias_cultivo'])) {
                $query->where('dia_inicial', '<=', $data['dias_cultivo'])
                      ->where('dia_final', '>=', $data['dias_cultivo']);
            }

            if (isset($data['arracoamento_perfil_id'])) {
                $query->where('arracoamento_perfil_id', $data['arracoamento_perfil_id']);
            }

        })
        ->orderBy('id')
        ->get();
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['arracoamento_perfil_id'])) {
                $query->where('arracoamento_perfil_id', $data['arracoamento_perfil_id']);
            }

        })
        ->orderBy('id')
        ->get();
    }
}
