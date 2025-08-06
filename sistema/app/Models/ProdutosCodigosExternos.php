<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutosCodigosExternos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'produtos_codigos_externos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'produto_id',
        'codigo_externo',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }
}
