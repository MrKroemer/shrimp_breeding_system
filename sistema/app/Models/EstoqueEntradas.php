<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EstoqueEntradas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'estoque_entradas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quantidade',
        'valor_unitario',
        'valor_total',
        'data_movimento',
        'tipo_origem',
        'produto_id',
        'filial_id',
        'usuario_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public function estoque_entrada_nota()
    {
        return $this->hasOne(EstoqueEntradasNotas::class, 'estoque_entrada_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function tipo_origem($tipo_origem = null)
    {
        $tipos_origens = [
            1   => 'Nota fiscal',
            2   => 'Importação',
            3   => 'Cancelamento',
        ];

        if (! $tipo_origem) {
            return $tipos_origens;
        }

        return $tipos_origens[$tipo_origem];
    }

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['produto_id'])) {
                $query->where('produto_id', $data['produto_id']);
            }

            if (isset($data['data_inicial']) && isset($data['data_final'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');
                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d');

                $query->whereBetween('data_movimento', [$data['data_inicial'], $data['data_final']]);

            } elseif (isset($data['data_inicial'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');

                $query->where('data_movimento', '>=', $data['data_inicial']);

            } elseif (isset($data['data_final'])) {

                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d');

                $query->where('data_movimento', '<=', $data['data_final']);

            }

            if (isset($data['tipo_origem'])) {
                $query->where('tipo_origem', $data['tipo_origem']);
            }

        })
        ->orderBy('data_movimento', 'desc')
        ->orderBy('id',             'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('data_movimento', 'desc')
        ->orderBy('id',             'desc')
        ->paginate($rowsPerPage);
    }
}
