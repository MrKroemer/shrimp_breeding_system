<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EstoqueEstornos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'estoque_estornos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quantidade',
        'valor_unitario',
        'valor_total',
        'data_movimento',
        'tipo_origem',
        'produto_id',
        'estoque_saida_id',
        'estorno_justificativa_id',
        'filial_id',
        'usuario_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }
    
    public function estoque_saida()
    {
        return $this->belongsTo(EstoqueSaidas::class, 'estoque_saida_id', 'id');
    }

    public function estoque_estorno_justificativa()
    {
        return $this->belongsTo(EstoqueEstornosJustificativas::class, 'estorno_justificativa_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
    }

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }

    public function tipo_origem()
    {
        $tipos_origens = [
            1 => 'Preparação',
            2 => 'Povoamento',
            3 => 'Arraçoamento',
            4 => 'Manejo',
            5 => 'Avulsa',
            6 => 'Descarte',
        ];

        if (! $this->tipo_origem) {
            return $tipos_origens;
        }

        return $tipos_origens[$this->tipo_origem];
    }

    public static function registrarEstorno($saidas_tipo, array $dados)
    {
        $exportacao = HistoricoExportacoes::verificaExportacoes($dados['tipo_destino'], $dados['data_movimento']);

        if (is_null($exportacao)) {

            if (! is_null($saidas_tipo)) {

                foreach ($saidas_tipo as $saida_tipo) {

                    $saida = $saida_tipo->estoque_saida;

                    if ($saida instanceof EstoqueSaidas) {

                        $estorno = static::create([
                            'quantidade'               => $saida->quantidade,
                            'valor_unitario'           => $saida->valor_unitario,
                            'valor_total'              => $saida->valor_total,
                            'data_movimento'           => $saida->data_movimento,
                            'tipo_origem'              => $saida->tipo_destino,
                            'produto_id'               => $saida->produto_id,
                            'estoque_saida_id'         => $saida->id,
                            'estorno_justificativa_id' => $dados['estorno_justificativa_id'],
                            'filial_id'                => $saida->filial_id,
                            'usuario_id'               => auth()->user()->id,
                        ]);

                        if ($estorno instanceof EstoqueEstornos) {

                            $saida->tipo_destino = 7; // Saídas estornadas

                            if ($saida->save()) {
                                continue;
                            }

                        }

                        return [4 => "A saída ID ({$saida->id}) do item {$saida->produto->nome} ($saida->produto_id) não foi estornada."];

                    }

                    return [3 => "Não foi encontrada uma saída registrada para o tipo de sáida ID ({$saida_tipo->id})."];

                }

                return [2 => "Estornos registrados com sucesso!"];

            }

            return [1 => "O tipo de saída não foi identificado."];

        }

        return [0 => "Estornos para está data ou anteriores não são permitidos, visto que os consumos de {$exportacao->tipo_exportacao()} do dia {$exportacao->data_exportacao()} já foram exportados pelo usuário " . mb_strtoupper($exportacao->usuario->nome) . "."];
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['estorno_justificativa_id'])) {
                $query->where('estorno_justificativa_id', $data['estorno_justificativa_id']);
            }

            if (isset($data['produto_id'])) {
                $query->where('produto_id', $data['produto_id']);
            }

            if (isset($data['tipo_origem'])) {
                $query->where('tipo_origem', $data['tipo_origem']);
            }

        })
        ->orderBy('id', 'desc')
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['estorno_justificativa_id'])) {
                $query->where('estorno_justificativa_id', $data['estorno_justificativa_id']);
            }

        })
        ->orderBy('produto_id', 'desc')
        ->paginate($rowsPerPage);
    }
}
