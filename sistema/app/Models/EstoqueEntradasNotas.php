<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueEntradasNotas extends Model
{
    public $timestamps = false;
    protected $table = 'estoque_entradas_notas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'estoque_entrada_id',
        'nota_fiscal_id',
    ];

    public function estoque_entrada()
    {
        return $this->belongsTo(EstoqueEntradas::class, 'estoque_entrada_id', 'id');
    }

    public function nota_fiscal()
    {
        return $this->belongsTo(NotasFiscais::class, 'nota_fiscal_id', 'id');
    }
}
