<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransferenciasPeixes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'transferencias_peixes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_movimento',
        'quantidade',
        'proporcao',
        'observacoes',
        'lote_peixes_id',
        'tanque_id',
        'filial_id',
        'usuario_id',
    ];

    public function lote_peixes()
    {
        return $this->belongsTo(LotesPeixes::class, 'lote_peixes_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }

    public function proporcao()
    {
        return round(($this->proporcao * 100), 2);
    }

    public static function listing(int $rowsPerPage = null)
    {
        return static::where(function ($query) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('data_movimento', 'desc')
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function search(array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['data_movimento'])) {
                $query->where('data_movimento', $data['data_movimento']);
            }

            if (isset($data['lote_peixes_id'])) {
                $query->where('lote_peixes_id', $data['lote_peixes_id']);
            }

        })
        ->orderBy('data_movimento', 'desc')
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
