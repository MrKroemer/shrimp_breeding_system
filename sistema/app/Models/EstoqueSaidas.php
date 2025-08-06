<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EstoqueSaidas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'estoque_saidas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quantidade',
        'valor_unitario',
        'valor_total',
        'data_movimento',
        'tipo_destino',
        'produto_id',
        'filial_id',
        'usuario_id',
        'tanque_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }

    public function tipo_destino($tipo_destino = null)
    {
        $tipos_destinos = [
            1 => 'Preparação',
            2 => 'Povoamento',
            3 => 'Arraçoamento',
            4 => 'Manejo',
            5 => 'Avulsa',
            6 => 'Descarte',
            7 => 'Estorno',
        ];

        if (! $tipo_destino) {
            $tipo_destino = $this->tipo_destino;
        }

        return $tipos_destinos[$tipo_destino];
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

        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function registrarSaida(string $classeSaida, array $produtos, array $dados)
    {
        $exportacao = HistoricoExportacoes::verificaExportacoes($dados['tipo_destino'], $dados['data_movimento']);

        // Não existir exportação de consumo ou tipo de destino for povoamento
        if (is_null($exportacao) || ($dados['tipo_destino'] == 2)) {
            
            foreach ($produtos as $produto) {

                $estoque = VwEstoqueDisponivel::verificaDisponibilidade($produto['produto_id']);

                if ($estoque instanceof VwEstoqueDisponivel) {

                    $dados['produto_id']     = $produto['produto_id'];
                    $dados['quantidade']     = $produto['quantidade'];
                    $dados['valor_unitario'] = $estoque->valor_unitario;
                    $dados['valor_total']    = ($dados['valor_unitario'] * $dados['quantidade']);
                    $dados['filial_id']      = session('_filial')->id;
                    $dados['usuario_id']     = auth()->user()->id;

                    $saida = static::create($dados);

                    if ($saida instanceof static) {

                        if ($saida->registrarTipoSaida($classeSaida, $dados)) {
                            continue;
                        }

                        $saida->delete();

                        return [4 => "Ocorreu um erro! O tipo de saída para o produto {$estoque->produto_nome} ({$estoque->produto_id}) não foi registrado."];

                    }

                    return [3 => "Ocorreu um erro! A saída para o produto {$estoque->produto_nome} ({$estoque->produto_id}) não foi registrada."];

                }

                return [2 => $estoque]; // Produto indisponível para uso

            }

            return [1 => "Saídas registradas com sucesso!"];

        }

        return [0 => "Saídas para está data ou anteriores não são permitidas, visto que os consumos de {$exportacao->tipo_exportacao()} do dia {$exportacao->data_exportacao()} já foram exportados pelo usuário " . mb_strtoupper($exportacao->usuario->nome) . "."];
    }

    public function registrarTipoSaida(string $classeSaida, array $dados)
    {
        $tipoSaida = null;

        switch ($classeSaida) {

            case 'App\Models\EstoqueSaidasPreparacoes':

                $tipoSaida = $classeSaida::create([
                    'estoque_saida_id'        => $this->id,
                    'tanque_id'               => $dados['tanque_id'],
                    'ciclo_id'                => $dados['ciclo_id'],
                    'preparacao_id'           => $dados['preparacao_id'],
                    'preparacao_aplicacao_id' => $dados['preparacao_aplicacao_id'],
                ]);

                break;

            case 'App\Models\EstoqueSaidasPovoamentos':

                $tipoSaida = $classeSaida::create([
                    'estoque_saida_id' => $this->id,
                    'tanque_id'        => $dados['tanque_id'],
                    'ciclo_id'         => $dados['ciclo_id'],
                    'lote_id'          => $dados['lote_id'],
                    'povoamento_id'    => $dados['povoamento_id'],
                ]);

                break;

            case 'App\Models\EstoqueSaidasArracoamentos':

                $tipoSaida = $classeSaida::create([
                    'estoque_saida_id' => $this->id,
                    'tanque_id'        => $dados['tanque_id'],
                    'ciclo_id'         => $dados['ciclo_id'],
                    'arracoamento_id'  => $dados['arracoamento_id'],
                ]);

                break;

            case 'App\Models\EstoqueSaidasManejos':

                $tipoSaida = $classeSaida::create([
                    'estoque_saida_id'    => $this->id,
                    'tanque_id'           => $dados['tanque_id'],
                    'aplicacao_insumo_id' => $dados['aplicacao_insumo_id'],
                ]);

                break;

            case 'App\Models\EstoqueSaidasAvulsas':

                $tipoSaida = $classeSaida::create([
                    'estoque_saida_id' => $this->id,
                    'tanque_id'        => $dados['tanque_id'],
                    'saida_avulsa_id'  => $dados['saida_avulsa_id'],
                ]);

                break;

        }

        if ($tipoSaida instanceof $classeSaida) {
            return true;
        }

        return false;
    }
}
