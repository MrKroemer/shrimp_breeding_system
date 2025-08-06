<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use League\Csv\Writer;
use DB;

class HistoricoExportacoes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'historico_exportacoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_exportacao',
        'tipo_exportacao',
        'filial_id',
        'usuario_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
    }

    public function data_exportacao(String $format = 'd/m/Y')
    {
        if (! $this->data_exportacao) {
            return $this->data_exportacao;
        }

        return Carbon::parse($this->data_exportacao)->format($format);
    }

    public function tipo_exportacao(bool $retornar_tipos = false)
    {
        $tipos_exportacoes = [
            1 => 'PRODUTOS QUÍMICOS',
            2 => 'RAÇÕES',
        ];

        if ($retornar_tipos) {
            return $tipos_exportacoes;
        }

        return $tipos_exportacoes[$this->tipo_exportacao];
    }

    public static function verificaExportacoes(int $tipo_destino, string $data_movimento)
    {
        $exportacoes = static::where('filial_id', session('_filial')->id)
        ->where('data_exportacao', '>=', $data_movimento)
        ->where(function ($query) use ($tipo_destino) {

            $query->where('tipo_exportacao', 1); // PRODUTOS QUÍMICOS

            if ($tipo_destino == 3) {
                $query->orWhere('tipo_exportacao', 2); // RAÇÕES
            }

        });

        $exportacoes->orderBy('data_exportacao', 'desc');

        $exportacao = $exportacoes->first();

        // Se existir exportação de consumo
        if ($exportacao instanceof static) {
            return $exportacao;
        }

        return null;
    }

    public static function geraArquivo(int $tipo_exportacao, string $data_exportacao, array $linhas_saidas, string $nome_arquivo)
    {
        if (count($linhas_saidas) > 0) {


            $historico_exportacao = static::create([
                'tipo_exportacao' => $tipo_exportacao,
                'data_exportacao' => $data_exportacao,
                'filial_id'       => session('_filial')->id,
                'usuario_id'      => auth()->user()->id,
            ]);

            if ($historico_exportacao instanceof static) {

                $caminho_arquivo = storage_path('tmp') . DIRECTORY_SEPARATOR . 'exportado.csv';
                $nome_arquivo .= '_' . $data_exportacao . date('_His') . '.txt';
                    
                $writer = Writer::createFromPath($caminho_arquivo, 'w+');
                $writer->setDelimiter(';');
                $writer->setNewline("\r\n");
                $writer->insertAll($linhas_saidas);

                return response()->download($caminho_arquivo, $nome_arquivo, ['Content-Type' => 'text/csv']);

            }

            return redirect()->back()
            ->with('error', 'Não foi possível registrar a exportação no histórico. Tente novamente.');

        }

        return redirect()->back()
        ->with('error', 'Não há registros de movimentações para a data informada.');
    }

    public static function exportaConsumoQuimicos(array $formData)
    {
        //Seta a Filial e local de estocagem
        $filial = 'M1';
        $local_estocagem = 'ET-PQ';
        if(session('_filial')->id == 2){
            $filial = 'M3';
            $local_estocagem = 'ET-PQ-AR';
        }

        $data_exportacao = Carbon::createFromFormat('d/m/Y', $formData['data_exportacao']);

        $data_movimento  = $data_exportacao->format('d-m-Y');
        $data_exportacao = $data_exportacao->format('Y-m-d');

        $arracoamentos = Arracoamentos::where('filial_id', session('_filial')->id)
        ->where('data_aplicacao', '<=', $data_exportacao)
        ->whereNotIn('situacao', ['V', 'B']);

        $aplicacoes_insumos = AplicacoesInsumos::where('filial_id', session('_filial')->id)
        ->where('data_aplicacao', '<=', $data_exportacao)
        ->whereNotIn('situacao', ['V', 'B']);

        $saidas_avulsas = SaidasAvulsas::where('filial_id', session('_filial')->id)
        ->where('data_movimento', '<=', $data_exportacao)
        ->whereNotIn('situacao', ['V', 'B']);

        if ($arracoamentos->count() > 0) {
            return redirect()->back()
            ->with('error', 'Existem arraçoamentos não validados na data informada ou anterior. Por favor, valide-os e tente novamente.');
        }

        if ($aplicacoes_insumos->count() > 0) {
            return redirect()->back()
            ->with('error', 'Existem aplicações de insumos não validados na data informada ou anterior. Por favor, valide-as e tente novamente.');
        }

        if ($saidas_avulsas->count() > 0) {
            return redirect()->back()
            ->with('error', 'Existem saídas avulsas não validados na data informada ou anterior. Por favor, valide-as e tente novamente.');
        }

        $estoque_saidas = VwEstoqueSaidas::where('filial_id', session('_filial')->id)
        ->where('data_movimento', $data_exportacao)
        ->where('tipo_destino', '<>', 7)           // Estorno
        ->where(function ($query) {
            $query->where('produto_tipo_id',   1); // INSUMOS
            $query->orWhere('produto_tipo_id', 3); // PROBIÓTICOS
            $query->orWhere('produto_tipo_id', 4); // OUTROS
        })
        ->whereIn('tanque_tipo_id', [1, 6])        // Viveiro de camarões e peixes
        ->get();

        $linhas_saidas = [];

        foreach ($estoque_saidas as $estoque_saida) {

            if (is_numeric($estoque_saida->ciclo_id) && $estoque_saida->ciclo_id > 0) {

                $tanque_ciclo = $estoque_saida->ciclo->tanque->sigla . '-' . $estoque_saida->ciclo->numero;
                $codigo_externo = trim($estoque_saida->codigo_externo ?: 'NO_CODE_' . $estoque_saida->produto_id);
                $quantidade = $estoque_saida->qtd_exportacao();

                
                $linhas_saidas[] = [
                    0 => $filial,
                    1 => $data_movimento,
                    2 => 'BP',
                    3 => $codigo_externo,
                    4 => $local_estocagem,
                    5 => $quantidade,
                    6 => $tanque_ciclo,
                ];

            }

        }

        $ciclos = session('_filial')->ciclosAtivos($data_exportacao, 1); // Viveiro de camarões

        $linhas_rateios = [];

        foreach ($ciclos as $ciclo) {

            $consumos_recebidos = $ciclo->consumosRecebidos($data_exportacao, $data_exportacao);

            foreach ($consumos_recebidos as $tanque_tipo_id => $produtos) {

                $local_estoque = 'ET-PQ';

                if(session('_filial')->id == 2){
                    $local_estoque = 'ET-PQ-AR';
                }
                

                // Reservatório de água salgada OU Bacia de sedimentação
                if ($tanque_tipo_id == 3 || $tanque_tipo_id == 4) {
                    $local_estoque = 'ETR-PQ';
                }

                foreach ($produtos as $produto_id => $quantidade) {

                    $produto = VwProdutos::find($produto_id);

                    if (
                        $produto->produto_tipo_id == 1 || // INSUMOS
                        $produto->produto_tipo_id == 3 || // PROBIÓTICOS
                        $produto->produto_tipo_id == 4    // OUTROS
                    ) {

                        $tanque_ciclo = $ciclo->tanque_sigla . '-' . $ciclo->ciclo_numero;
                        $codigo_externo = trim($produto->codigo_externo ?: 'NO_CODE_' . $produto->id);
                        $quantidade = round($quantidade, 4);

                        $linhas_rateios[] = [
                            0 => $filial,
                            1 => $data_movimento,
                            2 => 'BP',
                            3 => $codigo_externo,
                            4 => $local_estoque,
                            5 => $quantidade,
                            6 => $tanque_ciclo,
                        ];

                    }

                }

            }

        }

        $linhas_saidas = array_merge($linhas_saidas, $linhas_rateios);

        return static::geraArquivo($formData['tipo_exportacao'], $data_exportacao, $linhas_saidas, 'baixaporconsumo_pq');
    }

    public static function exportaConsumoRacoes(array $formData)
    {
        //Seta a Filial
        $filial = 'M1';
        if(session('_filial')->id == 2){
            $filial = 'M3';
        }

        $data_exportacao = Carbon::createFromFormat('d/m/Y', $formData['data_exportacao']);

        $data_movimento  = $data_exportacao->format('d-m-Y');
        $data_exportacao = $data_exportacao->format('Y-m-d');

        $arracoamentos = Arracoamentos::where('filial_id', session('_filial')->id)
        ->where('data_aplicacao', '<=', $data_exportacao)
        ->whereNotIn('situacao', ['V', 'B']);

        if ($arracoamentos->count() > 0) {
            return redirect()->back()
            ->with('error', 'Existem arraçoamentos não validados na data informada ou anterior. Por favor, valide-os e tente novamente.');
        }

        $estoque_saidas = VwEstoqueSaidas::where('filial_id', session('_filial')->id)
        ->where('data_movimento', $data_exportacao)
        ->where('tipo_destino', '<>', 7) // Estorno
        ->where('produto_tipo_id', 2)    // RAÇÕES
        ->where('tanque_tipo_id', 1)     // Viveiro de camarões
        ->get();

        $linhas_saidas = [];

        foreach ($estoque_saidas as $estoque_saida) {

            if (is_numeric($estoque_saida->ciclo_id) && $estoque_saida->ciclo_id > 0) {

                $tanque_ciclo   = $estoque_saida->ciclo->tanque->sigla . '-' . $estoque_saida->ciclo->numero;
                $codigo_externo = trim($estoque_saida->codigo_externo ?: 'NO_CODE_' . $estoque_saida->produto_id);
                $local_estoque  = trim($estoque_saida->ciclo->tanque_id . '-' . $estoque_saida->ciclo->tanque->sigla);
                $quantidade     = $estoque_saida->qtd_exportacao();

                $linhas_saidas[] = [
                    0 => $filial,
                    1 => $data_movimento,
                    2 => 'BP',
                    3 => $codigo_externo,
                    4 => $local_estoque,
                    5 => $quantidade,
                    6 => $tanque_ciclo,
                ];

            }

        }

        $ciclos = session('_filial')->ciclosAtivos($data_exportacao, 1); // Viveiro de camarões

        $linhas_rateios = [];

        foreach ($ciclos as $ciclo) {

            $consumos_recebidos = $ciclo->consumosRecebidos($data_exportacao, $data_exportacao);

            foreach ($consumos_recebidos as $tanque_tipo_id => $produtos) {

                foreach ($produtos as $produto_id => $quantidade) {

                    $produto = VwProdutos::find($produto_id);

                    if ($produto->produto_tipo_id == 2) { // RAÇÕES
    
                        $tanque_ciclo = $ciclo->tanque_sigla . '-' . $ciclo->ciclo_numero;
                        $codigo_externo = trim($produto->codigo_externo ?: 'NO_CODE_' . $produto->id);
                        $local_estoque = trim($ciclo->tanque_id . '-' . $ciclo->tanque->sigla);
                        $quantidade = round($quantidade, 4);
    
                        $linhas_rateios[] = [
                            0 => $filial,
                            1 => $data_movimento,
                            2 => 'BP',
                            3 => $codigo_externo,
                            4 => $local_estoque,
                            5 => $quantidade,
                            6 => $tanque_ciclo,
                        ];

                    }

                }

            }

        }

        $linhas_saidas = array_merge($linhas_saidas, $linhas_rateios);

        return static::geraArquivo($formData['tipo_exportacao'], $data_exportacao, $linhas_saidas, 'baixaporconsumo');
    }

    public static function search(array $data, int $rowsPerPage = null)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['data_inicial']) && isset($data['data_final'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');
                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d');

                $query->whereBetween('data_exportacao', [$data['data_inicial'], $data['data_final']]);

            } elseif (isset($data['data_inicial'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');

                $query->where('data_exportacao', '>=', $data['data_inicial']);

            } elseif (isset($data['data_final'])) {

                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d');

                $query->where('data_exportacao', '<=', $data['data_final']);

            }

            if (isset($data['tipo_exportacao'])) {
                $query->where('tipo_exportacao', $data['tipo_exportacao']);
            }

        })
        ->orderBy('data_exportacao', 'desc')
        ->orderBy('id',              'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null)
    {
        return static::where(function ($query) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('data_exportacao', 'desc')
        ->orderBy('id',              'desc')
        ->paginate($rowsPerPage);
    }
}
