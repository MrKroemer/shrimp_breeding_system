<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwEstoqueDisponivel extends Model
{
    protected $table = 'vw_estoque_disponivel';
    protected $primaryKey = 'produto_id';

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public function taxas_custos(){

        return $this->belongsTo(TaxasCustos::class, 'taxas_custos_id', 'id');
    }   

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function unidade_entrada()
    {
        return $this->belongsTo(UnidadesMedidas::class, 'produto_und_ent_id', 'id');
    }

    public function unidade_saida()
    {
        return $this->belongsTo(UnidadesMedidas::class, 'produto_und_sai_id', 'id');
    }

    public function ultima_entrada(String $format = 'd/m/Y')
    {
        if (! $this->ultima_entrada) {
            return $this->ultima_entrada;
        }

        return Carbon::parse($this->ultima_entrada)->format($format);
    }

    public function valor_unitario()
    {   
            if($this->em_estoque != 0){
                return ($this->valor_estoque / $this->em_estoque);
            }

       return $this->valor_unitario;
    }

    public function valor_estoque(){
        
        if ($this->em_estoque != 0) {
            return ($this->valor_estoque / $this->em_estoque);
        }

        return $this->valor_estoque;
    }


       public static function disponivel(int $produto_id, int $filial_id)
    {
        $estoque_disponivel = static::produtosAtivos($filial_id)
        ->where('produto_id', $produto_id)
        ->first();

        if (! is_null($estoque_disponivel)) {

            if (! empty((float) $estoque_disponivel->em_estoque)) {
                return $estoque_disponivel; // Caso o produto não esteja com estoque zerado.
            }

        }

        return null;
    }

    public static function verificaDisponibilidade(int $produto_id)
    {
        //$produto = static::find($produto_id);
        $produto = static::where('produto_id', $produto_id)
        ->where('filial_id', session('_filial')->id)
        ->first();

        if ($produto instanceof static) {

            if ($produto->produto_situacao == 'ON') {

                if ($produto->em_estoque != 0) {
                    return $produto;
                }

                return "O produto {$produto->produto_nome} ({$produto->produto_id}) não possui saldo em estoque.";

            }

            return "O produto {$produto->produto_nome} ({$produto->produto_id}) está inativo.";

        }

        return "Não existe um produto com o ID {$produto_id}, registrado no sistema.";
    }

    public static function produtosAtivos(int $filial_id)
    {
        return static::where('produto_situacao', 'ON')
        ->where('filial_id', $filial_id);
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['produto_id'])) {

                $query->where(function ($query) use ($data) {
                    $query->where('produto_id', $data['produto_id'])
                    ->orWhere('codigo_externo', $data['produto_id']);
                });

            }

            if (isset($data['produto_nome'])) {

                $data['produto_nome'] = mb_strtoupper($data['produto_nome']);

                $query->where(function ($query) use ($data) {
                    $query->where('produto_nome', 'LIKE', "%{$data['produto_nome']}%")
                    ->orWhere('produto_sigla', 'LIKE', "%{$data['produto_nome']}%");
                });

            }

            if (isset($data['produto_tipo_id'])) {
                $query->where('produto_tipo_id', $data['produto_tipo_id']);
            }

        })
        ->orderBy('produto_nome')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('produto_nome')
        ->paginate($rowsPerPage);
    }
}
