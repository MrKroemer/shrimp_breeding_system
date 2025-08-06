<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transferencias extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'transferencias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_movimento',
        'situacao',
        'observacoes',
        'quantidade',
        'proporcao',
        'ciclo_origem_id',
        'ciclo_destino_id',
        'usuario_id',
    ];

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }

    public function situacao($situacao = null)
    {
        $situacoes = [
            'V' => 'Validado',
            'N' => 'Não validado',
        ];

        if (! $situacao) {
            return $situacoes;
        }

        return $situacoes[$situacao];
    }

    public function ciclo_origem()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_origem_id', 'id');
    }

    public function ciclo_destino()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_destino_id', 'id');
    }

    public function transferencias_enviadas() {

        $transferencias = static::where('ciclo_origem_id', $this->ciclo_origem_id)
        ->where('data_movimento','<=', $this->data_movimento)
        ->orderBy('data_movimento')
        ->get();

        
        $contador = 0;
        $valor_acumulado = 0;
        $data = "";
        
        if ($transferencias->count() > 1){
             
            foreach ($transferencias as $transferencia){
                
                if($contador < $transferencias->count()){

                    if($transferencia->proporcao < 1){
                        $valor_acumulado = ($valor_acumulado + $transferencia->consumo_destino($data)) * (1 - $transferencia->proporcao);
                    }else{
                        $valor_acumulado = ($valor_acumulado + $transferencia->consumo_destino($data)) * $transferencia->proporcao;
                    }
                    
                    $data = $transferencia->data_movimento;

                }
                $contador++;
                
            }
            return $valor_acumulado;
            
        }

        return $this->consumo_destino() * $this->proporcao;

    }


    public function consumo_destino(string $data = null)
    {
        if($data){
            return $this->custo_destino()->where('data_movimento','<=',$this->data_movimento)->where('data_movimento','>',$data)->sum('valor_total');
        }else{
            return $this->custo_destino()->where('data_movimento','<=',$this->data_movimento)->sum('valor_total');
        }
       
    }

    public function custo_destino()
    {
        return $this->hasMany(VwCustosPeixes::class, 'ciclo_id', 'ciclo_origem_id');
    }

    public function aplicacoes()
    {
        return $this->hasMany(PreparacoesAplicacoes::class, 'preparacao_id', 'id');
    }

    public function estoque_saidas_preparacoes()
    {
        return $this->hasMany(EstoqueSaidasPreparacoes::class, 'preparacao_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['ciclo_origem_id'])) {
                $query->where('ciclo_origem_id', $data['ciclo_origem_id']);
            }

            if (isset($data['ciclo_destino_id'])) {
                $query->where('ciclo_destino_id', $data['ciclo_destino_id']);
            }

            if (isset($data['data_movimento'])) {
                $data['data_movimento'] = Carbon::createFromFormat('d/m/Y', $data['data_movimento'])->format('Y-m-d');
            }

        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            // 
        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }
}
