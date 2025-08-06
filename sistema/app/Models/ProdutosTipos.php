<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutosTipos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'produtos_tipos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome'
    ];
}
