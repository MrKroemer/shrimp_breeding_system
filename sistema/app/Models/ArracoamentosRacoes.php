<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArracoamentosRacoes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'arracoamentos_racoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'porcentagem',
        'produto_id',
        'arracoamento_esquema_id',
    ];

    public function racao()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public function arracoamento_esquema()
    {
        return $this->belongsTo(ArracoamentosEsquemas::class, 'arracoamento_esquema_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['arracoamento_esquema_id'])) {
                $query->where('arracoamento_esquema_id', $data['arracoamento_esquema_id']);
            }
            
        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }
}
