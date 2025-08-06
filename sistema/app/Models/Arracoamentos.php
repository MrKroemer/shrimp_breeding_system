<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Arracoamentos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'arracoamentos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_aplicacao',
        'situacao',
        'observacoes',
        'mortalidade',
        'ciclo_id',
        'tanque_id',
        'filial_id',
        'usuario_id',
    ];

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function horarios()
    {
        return $this->hasMany(ArracoamentosHorarios::class, 'arracoamento_id', 'id');
    }

    public function aplicacoes()
    {
        return $this->hasMany(ArracoamentosAplicacoes::class, 'arracoamento_id', 'id');
    }

    public function estoque_saidas_arracoamentos()
    {
        return $this->hasMany(EstoqueSaidasArracoamentos::class, 'arracoamento_id', 'id');
    }

    public function qtdTotalRacaoProgramada()
    {
        $aplicacoes = $this->aplicacoes;
        
        $quantidade = 0;
        
        if ($aplicacoes->isNotEmpty()) {
            foreach ($aplicacoes as $aplicacao) {
                $quantidade += $aplicacao->qtdRacaoPorAplicacao(1); // Quantidade programada
            }
        }

        return $quantidade;
    }

    public function qtdTotalRacaoAplicada()
    {
        $aplicacoes = $this->aplicacoes;
        
        $quantidade = 0;
        
        if ($aplicacoes->isNotEmpty()) {
            foreach ($aplicacoes as $aplicacao) {
                $quantidade += $aplicacao->qtdRacaoPorAplicacao(); // Quantidade aplicada (validada)
            }
        }

        return $quantidade;
    }

    public function qtdQuilosPorRacao()
    {
        $aplicacoes = $this->aplicacoes;

        if ($aplicacoes->isNotEmpty()) {

            $racoes = [];

            foreach ($aplicacoes as $aplicacao) {

                $aplicacao_produto = $aplicacao->arracoamento_aplicacao_produto;

                if (! empty($aplicacao_produto)) {
                
                    $produto = Produtos::find($aplicacao_produto->produto_id);

                    if ($produto->produto_tipo_id == 2) {

                        if (! isset($racoes[$produto->id]['nome']) && ! isset($racoes[$produto->id]['quantidade'])) {
                            $racoes[$produto->id]['nome'] = $produto->nome;
                            $racoes[$produto->id]['quantidade'] = 0;
                        }

                        $racoes[$produto->id]['quantidade'] += $aplicacao_produto->quantidade;

                    }

                }
                
            }
            
            return $racoes;
        }

        return null;
    }

    public function qtdProbioticoPorPeriodo()
    {
        $aplicacoes = $this->aplicacoes;

        if ($aplicacoes->isNotEmpty()) {
        
            $probiotico = [];

            $count = 0;

            foreach ($aplicacoes as $aplicacao) {

                if ($aplicacao->arracoamento_aplicacao_tipo_id == 2) { // PROBIÓTICO

                    $receita_id      = $aplicacao->arracoamento_aplicacao_receita->receita_laboratorial_id;
                    $receita_nome    = $aplicacao->arracoamento_aplicacao_receita->receita_laboratorial->nome;
                    $receita_unidade = $aplicacao->arracoamento_aplicacao_receita->receita_laboratorial->unidade_medida->sigla;
                    $quantidade      = $aplicacao->arracoamento_aplicacao_receita->quantidade;

                    if (! isset($probiotico[$receita_id])) {
                        $probiotico[$receita_id]['receita_id']      = $receita_id;
                        $probiotico[$receita_id]['receita_nome']    = $receita_nome;
                        $probiotico[$receita_id]['receita_unidade'] = $receita_unidade;
                        $probiotico[$receita_id]['qtd_manha']       = 0;
                        $probiotico[$receita_id]['qtd_tarde']       = 0;
                    }

                    if ($count < 2) {
                        $probiotico[$receita_id]['qtd_manha'] += $quantidade;
                    } else {
                        $probiotico[$receita_id]['qtd_tarde'] += $quantidade;
                    }

                    $count ++;
                    
                }

            }

            return $probiotico;
        }

        return null;
    }

    public function qtdAditivoPorPeriodo()
    {
        $aplicacoes = $this->aplicacoes;

        if ($aplicacoes->isNotEmpty()) {
        
            $aditivos = [];

            $count = 0;

            foreach ($aplicacoes as $aplicacao) {

                if ($aplicacao->arracoamento_aplicacao_tipo_id == 3) { // ADITIVO

                    $receita_id      = $aplicacao->arracoamento_aplicacao_receita->receita_laboratorial_id;
                    $receita_nome    = $aplicacao->arracoamento_aplicacao_receita->receita_laboratorial->nome;
                    $receita_unidade = $aplicacao->arracoamento_aplicacao_receita->receita_laboratorial->unidade_medida->sigla;
                    $quantidade      = $aplicacao->arracoamento_aplicacao_receita->quantidade;

                    if (! isset($aditivos[$receita_id])) {
                        $aditivos[$receita_id]['receita_id']      = $receita_id;
                        $aditivos[$receita_id]['receita_nome']    = $receita_nome;
                        $aditivos[$receita_id]['receita_unidade'] = $receita_unidade;
                        $aditivos[$receita_id]['qtd_manha']       = 0;
                        $aditivos[$receita_id]['qtd_tarde']       = 0;
                    }

                    if ($count < 2) {
                        $aditivos[$receita_id]['qtd_manha'] += $quantidade;
                    } else {
                        $aditivos[$receita_id]['qtd_tarde'] += $quantidade;
                    }

                    $count ++;

                }

            }

            return $aditivos;
        }

        return null;
    }

    public function data_aplicacao(String $format = 'd/m/Y')
    {
        if (! $this->data_aplicacao) {
            return $this->data_aplicacao;
        }

        return Carbon::parse($this->data_aplicacao)->format($format);
    }

    public function situacao($situacao = null)
    {
        $situacoes = [
            'V' => 'Validado',
            'P' => 'Parcialmente validado',
            'N' => 'Não validado',
            'B' => 'Bloqueado',
        ];

        if (! $situacao) {
            return $situacoes;
        }

        return $situacoes[$situacao];
    }

    public function removeAplicacoes()
    {
        foreach ($this->horarios as $horario) {
            
            foreach ($horario->aplicacoes as $aplicacao) {

                if (! empty($aplicacao->arracoamento_aplicacao_produto)) {
                    $aplicacao->arracoamento_aplicacao_produto->delete();
                }

                if (! empty($aplicacao->arracoamento_aplicacao_receita)) {
                    $aplicacao->arracoamento_aplicacao_receita->delete();
                }

                $aplicacao->delete();
                
            }

            $horario->delete();

        }
    }

    public static function search(Array $data, int $rowsPerPage = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['data_aplicacao'])) {
                $query->where('data_aplicacao', $data['data_aplicacao']);
            }

            if (isset($data['setor_id'])) {

                $tanques = Tanques::where('setor_id', $data['setor_id'])->get(['id']);

                if ($tanques->isNotEmpty()) {
                    $query->whereIn('tanque_id', $tanques);
                }

            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('data_aplicacao', 'desc')
        ->orderBy('ciclo_id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['data_aplicacao'])) {
                $query->where('data_aplicacao', $data['data_aplicacao']);
            }

            if (isset($data['setor_id'])) {

                $tanques = Tanques::where('setor_id', $data['setor_id'])->get(['id']);

                if ($tanques->isNotEmpty()) {
                    $query->whereIn('tanque_id', $tanques);
                }

            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('data_aplicacao', 'desc')
        ->orderBy('ciclo_id', 'desc')
        ->paginate($rowsPerPage);
    }
}
