<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArracoamentosHorarios extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'arracoamentos_horarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ordinal',
        'arracoamento_id',
    ];
    
    public function arracoamento()
    {
        return $this->belongsTo(Arracoamentos::class, 'arracoamento_id', 'id');
    }

    public function aplicacoes()
    {
        return $this->hasMany(ArracoamentosAplicacoes::class, 'arracoamento_horario_id', 'id');
    }

    public function qtdRacaoPorHorario()
    {
        $aplicacoes = $this->aplicacoes;

        if ($aplicacoes->isNotEmpty()) {

            $quantidade = 0;

            foreach ($aplicacoes as $aplicacao) {
                $quantidade += $aplicacao->qtdRacaoPorAplicacao(1);
            }

            return $quantidade;

        }

        return 0;
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['arracoamento_id'])) {
                $query->where('arracoamento_id', $data['arracoamento_id']);
            }

        })
        ->orderBy('ordinal', 'asc')
        ->paginate($rowsPerPage);
    }
}
