<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'produtos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'sigla',
        'situacao',
        'unidade_razao',
        'produto_tipo_id',
        'unidade_entrada_id',
        'unidade_saida_id',
        'filial_id',
        'usuario_id',
    ];

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

    public function codigos_externos()
    {
        return $this->hasMany(ProdutosCodigosExternos::class, 'produto_id', 'id');
    }

    public function getCodigoExterno()
    {
        $produto_codigo_externo = $this->codigos_externos->sortByDesc('criado_em')->first();

        if (! empty($produto_codigo_externo)) {
            return $produto_codigo_externo->codigo_externo;
        }

        return null;
    }

    public function saveCodigoExterno(int $codigo_externo)
    {
        if (! empty($codigo_externo)) {

            if ($this->getCodigoExterno() != $codigo_externo) {

                $produto_codigo_externo = ProdutosCodigosExternos::create([
                    'produto_id'     => $this->id,
                    'codigo_externo' => $codigo_externo,
                ]);

                if ($produto_codigo_externo instanceof ProdutosCodigosExternos) {
                    return true;
                }

            }

        }

        return false;
    }

    public function deleteCodigoExterno()
    {
        $produto_codigo_externo = ProdutosCodigosExternos::where('produto_id', $this->id);

        if ($produto_codigo_externo->get()->isEmpty()) {
            return true;
        }

        if ($produto_codigo_externo->delete()) {
            return true;
        }

        return false;
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['nome'])) {

                $data['nome'] = mb_strtoupper($data['nome']);

                $query->where('nome', 'LIKE', "%{$data['nome']}%");

            }

            if (isset($data['sigla'])) {

                $data['sigla'] = mb_strtoupper($data['sigla']);

                $query->where('sigla', 'LIKE', "%{$data['sigla']}%");

            }

            $query->where('filial_id', session('_filial')->id);

        })
        ->orderBy('nome', 'asc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('nome', 'asc')
        ->paginate($rowsPerPage);
    }

    public static function ativos()
    {
        return static::where('situacao', 'ON');
    }
}
