<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SaidasAvulsasProdutos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'saidas_avulsas_produtos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quantidade',
        'produto_id',
        'saida_avulsa_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public function saida_avulsa()
    {
        return $this->belongsTo(SaidasAvulsas::class, 'saida_avulsa_id', 'id');
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

            if (isset($data['saida_avulsa_id'])) {
                $query->where('saida_avulsa_id', $data['saida_avulsa_id']);
            }

        })
        ->orderBy('id', 'asc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['saida_avulsa_id'])) {
                $query->where('saida_avulsa_id', $data['saida_avulsa_id']);
            }

        })
        ->orderBy('id', 'asc')
        ->paginate($rowsPerPage);
    }
}
