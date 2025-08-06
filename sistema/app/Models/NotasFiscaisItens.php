<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotasFiscaisItens extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'notas_fiscais_itens';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quantidade',
        'valor_unitario',
        'valor_frete',
        'valor_desconto',
        'produto_id',
        'nota_fiscal_id',
        'usuario_id',
    ];

    public function nota_fiscal()
    {
        return $this->belongsTo(NotasFiscais::class, 'nota_fiscal_id', 'id');
    }

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['nota_fiscal_id'])) {
                $query->where('nota_fiscal_id', $data['nota_fiscal_id']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['nota_fiscal_id'])) {
                $query->where('nota_fiscal_id', $data['nota_fiscal_id']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }
}
