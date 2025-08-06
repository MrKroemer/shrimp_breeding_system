<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CertificacaoReprodutores extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'certificacao_reprodutores';
    protected $primaryKey = 'id';
    protected $fillable = [
        'numero_certificacao',
        'plantel',
        'familia',
        'data_estresse',
        'data_coleta',
        'data_certificacao',
        'tanque_origem_id',
        'tanque_maturacao_id',
        'filial_id',
        'usuario_id',
        'situacao',
        'imagem_certificacao',
    ];

    public function data_coleta(String $format = 'd/m/Y')
    {
        if (! $this->data_coleta) {
            return $this->data_coleta;
        }

        return Carbon::parse($this->data_coleta)->format($format);
    }
    
    public function data_estresse(String $format = 'd/m/Y')
    {
        if (! $this->data_estresse) {
            return $this->data_estresse;
        }

        return Carbon::parse($this->data_estresse)->format($format);
    }
    
    public function data_certificacao(String $format = 'd/m/Y')
    {
        if (! $this->data_certificacao) {
            return $this->data_certificacao;
        }

        return Carbon::parse($this->data_certificacao)->format($format);
    }
    public function reprodutores()
    {
        return $this->hasMany(Reprodutores::class, 'certificacao_reprodutores_id', 'id');
    }

    public function tanque_origem()
    {
        return $this->belongsTo(Tanques::class, 'tanque_origem_id', 'id');
    }
    
    public function tanque_maturacao()
    {
        return $this->belongsTo(Tanques::class, 'tanque_maturacao_id', 'id');
    }

    public function situacao($situacao = null)
    {
        $situacoes = [
            1 => 'Em análise', 
            2 => 'Certificado', 
            3 => 'Encerrado',
        ];

        if (! $situacao) {
            $situacao = $this->situacao;
        }

        return $situacoes[$situacao];
    }

    public function status()
    {
        $status = 0;
        
        //dd(! is_null($this->data_certificacao));

        if((! is_null($this->data_coleta)) && (! is_null($this->data_estresse)) && (! is_null($this->data_certificacao)))
        {
            $data_coleta = Carbon::createFromFormat('Y-m-d', $this->data_coleta);
            $data_estresse = Carbon::createFromFormat('Y-m-d', $this->data_estresse);
            $data_certificacao = Carbon::createFromFormat('Y-m-d', $this->data_certificacao);
            
            //testa a data de coleta e stress
            if($data_coleta->diffInDays($data_estresse) > 4){
                $status = 1;
            }

            //testa a data do stress e a data da certificação
            if($data_estresse->diffInDays($data_certificacao) > 5){
                $status = 1;
            }

            return $status;

            
        }

        return 0;
        
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['numero_certificacao'])) {
                $query->where('numero_certificacao', $data['numero_certificacao']);
            }
            
            if (isset($data['familia'])) {
                $query->where('familia', $data['familia']);
            }
            
            if (isset($data['tanque_origem_id'])) {
                $query->where('tanque_origem_id', $data['tanque_origem_id']);
            }
            
            if (isset($data['tanque_maturacao_id'])) {
                $query->where('tanque_maturacao_id', $data['tanque_maturacao_id']);
            }

        })
        ->orderBy('numero_certificacao', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            // 
        })
        ->orderBy('numero_certificacao', 'desc')
        ->paginate($rowsPerPage);
    }
}