<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwProdutos extends Model
{
    protected $table = 'vw_produtos';
    protected $primaryKey = 'id';

    public function produto_tipo()
    {
        return $this->belongsTo(ProdutosTipos::class, 'produto_tipo_id', 'id');
    }

    public function unidade_entrada()
    {
        return $this->belongsTo(UnidadesMedidas::class, 'unidade_entrada_id', 'id');
    }

    public function unidade_saida()
    {
        return $this->belongsTo(UnidadesMedidas::class, 'unidade_saida_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['id'])) {
                
                $query->where(function ($query) use ($data) {
                    $query->where('id', $data['id'])
                    ->orWhere('codigo_externo', $data['id']);
                });

            }

            if (isset($data['nome'])) {

                $data['nome'] = mb_strtoupper($data['nome']);

                $query->where(function ($query) use ($data) {
                    $query->where('nome', 'LIKE', "%{$data['nome']}%")
                    ->orWhere('sigla', 'LIKE', "%{$data['nome']}%");
                });

            }

            if (isset($data['produto_tipo_id'])) {
                $query->where('produto_tipo_id', $data['produto_tipo_id']);
            }

        })
        ->orderBy('nome')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('nome')
        ->paginate($rowsPerPage);
    }

}
