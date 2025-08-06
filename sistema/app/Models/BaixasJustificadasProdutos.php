<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BaixasJustificadasProdutos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'baixas_justificadas_produtos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quantidade',
        'produto_id',
        'baixa_justificada_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public function baixa_justificada()
    {
        return $this->belongsTo(BaixasJustificadas::class, 'baixa_justificada_id', 'id');
    }

    public function alterado_em(String $format = 'd/m/Y')
    {
        if (! $this->alterado_em) {
            return $this->alterado_em;
        }

        return Carbon::parse($this->alterado_em)->format($format);
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['produto_id'])) {
                $query->where('produto_id', $data['produto_id']);
            }

            if (isset($data['baixa_justificada_id'])) {
                $query->where('baixa_justificada_id', $data['baixa_justificada_id']);
            }

        })
        ->orderBy('id', 'asc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['baixa_justificada_id'])) {
                $query->where('baixa_justificada_id', $data['baixa_justificada_id']);
            }

        })
        ->orderBy('id', 'asc')
        ->paginate($rowsPerPage);
    }
}
