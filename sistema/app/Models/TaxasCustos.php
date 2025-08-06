<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TaxasCustos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'taxas_custos';
    protected $primaryKey = 'id';
    protected $fillable = [

        'data_referencia',
        'custo_fixo',
        'custo_energia',
        'custo_combustivel',
        'custo_depreciacao',
        'usuario_id',
        'filial_id',
        /*'racao_fgcb',
        'racao_starter',
        'racao_fgar',
        'bkc_camanor',
        'starter_peletizada',*/
    ];

    public function data_referencia(String $format = 'd/m/Y')
    {
        if (! $this->data_referencia) {
            return $this->data_referencia;
        }

        return Carbon::parse($this->data_referencia)->format($format);
    }
    
    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['data_referencia'])) {
                $query->where('data_referencia', "%{$data['data_referencia']}%");
            }

        })
        ->orderBy('data_referencia', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('data_referencia', 'desc')
        ->paginate($rowsPerPage);
    }
}