<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientesFornecedores extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'clientes_fornecedores';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'razao',
        'cnpj',
        'ie',
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'telefone',
        'tipo',
        'cidade_id',
        'estado_id',
        'pais_id',
    ];

    public function pais()
    {
        return $this->belongsTo(Paises::class, 'pais_id', 'id');
    }
	
	public function estado()
    {
        return $this->belongsTo(Estados::class, 'estado_id', 'id');
    }
	
	public function cidade()
    {
        return $this->belongsTo(Cidades::class, 'cidade_id', 'id');
    }

    public function tipo($tipo = null)
    {
        $tipos = [
            'C'   => 'Cliente',
            'F'   => 'Fornecedor',
            'C|F' => 'Cliente/Fornecedor',
        ];

        if (!$tipo) {
            return $tipos;
        }

        return $tipos[$tipo];
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['nome'])) {
                $query->where('nome', 'LIKE', "%{$data['nome']}%");
            }

            if (isset($data['razao'])) {
                $query->where('razao', 'LIKE', "%{$data['razao']}%");
            }

            if (isset($data['cnpj'])) {
                $query->where('cnpj', $data['cnpj']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            // 
        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }
}
