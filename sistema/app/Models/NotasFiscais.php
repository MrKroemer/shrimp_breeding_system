<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NotasFiscais extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'notas_fiscais';
    protected $primaryKey = 'id';
    protected $fillable = [
		'chave',
        'numero',
		'data_emissao',
        'data_movimento',
		'valor_total',
		'valor_frete',
		'valor_desconto',
		'icms_desonerado',
        'situacao',
		'fornecedor_id',
        'cliente_id',
        'usuario_id',
        'filial_id',
    ];

    public function data_emissao(String $format = 'd/m/Y')
    {
        if (! $this->data_emissao) {
            return $this->data_emissao;
        }

        return Carbon::parse($this->data_emissao)->format($format);
    }

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }

    public function fornecedor()
    {
        return $this->belongsTo(ClientesFornecedores::class, 'fornecedor_id', 'id');
    }
	
	public function cliente()
    {
        return $this->belongsTo(ClientesFornecedores::class, 'cliente_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function nota_fiscal_itens()
    {
        return $this->hasMany(NotasFiscaisItens::class, 'nota_fiscal_id', 'id');
    }

    public function estoque_entradas_nota()
    {
        return $this->hasMany(EstoqueEntradasNotas::class, 'nota_fiscal_id', 'id');
    }

    public function situacao($situacao = null)
    {
        $situacoes = [
            'A'  => 'Aberta',
            'F'  => 'Fechada',
            'E'  => 'Estornada',
        ];

        if (!$situacao) {
            return $situacoes;
        }

        return $situacoes[$situacao];
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['numero'])) {
                $query->where('numero', $data['numero']);
            }

            if (isset($data['chave'])) {
                $query->where('chave', $data['chave']);
            }

            if (isset($data['data_emissao'])) {

                $data['data_emissao'] = Carbon::createFromFormat('d/m/Y', $data['data_emissao'])->format('Y-m-d');
                
                $query->where('data_emissao', $data['data_emissao']);

            }

            if (isset($data['data_movimento'])) {
                $query->where('data_movimento', $data['data_movimento']);
            }

            if (isset($data['filial_cnpj'])) {

                $cliente = ClientesFornecedores::where('cnpj', $data['filial_cnpj'])->first();

                if ($cliente) {
                    $query->where('cliente_id', $cliente->id);
                }

            }

            if (isset($data['situacao'])) {
                
                $situacoes = explode('|', $data['situacao']);

                $query->where(function ($query) use ($situacoes){

                    foreach ($situacoes as $index => $situacao) {

                        switch ($index) {
                            case 0:
                                $query->where('situacao', $situacao);
                                break;
                            default:
                                $query->orWhere('situacao', $situacao);
                                break;
                        }
                        
                    }

                });

            }
            
            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['situacao'])) {
                
                $situacoes = explode('|', $data['situacao']);

                $query->where(function ($query) use ($situacoes) {

                    foreach ($situacoes as $index => $situacao) {

                        switch ($index) {
                            case 0:
                                $query->where('situacao', $situacao);
                                break;
                            default:
                                $query->orWhere('situacao', $situacao);
                                break;
                        }
                        
                    }

                });

            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
