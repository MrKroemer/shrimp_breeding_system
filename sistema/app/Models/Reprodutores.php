<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reprodutores extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'reprodutores';
    protected $primaryKey = 'id';
    protected $fillable = [
        'item_tubo',
        'sexo',
        'numero_anel',
        'cor_anel',
        'observacao',
        'certificacao_reprodutores_id',
        'usuario_id',
    ];

    public function certificacao_reprodutores()
    {
        return $this->belongsTo(CertificacaoReprodutores::class, 'certificacao_reprodutores_id', 'id');
    }

    public function amostras()
    {
        return $this->hasMany(ReprodutoresAnalisesAmostras::class, 'reprodutor_id', 'id');
    }

    public static function search(Array $data)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['filtro_sexo'])) {
                $query->where('sexo', $data['filtro_sexo']); 
            }

            if (isset($data['filtro_cor_anel'])) {
                $query->where('cor_anel', $data['filtro_cor_anel']);
            }

        })
        ->orderBy('item_tubo', 'desc');
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            // 
        })
        ->orderBy('numero_anel', 'desc')
        ->paginate($rowsPerPage);
    }
}

