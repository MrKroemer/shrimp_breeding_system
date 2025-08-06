<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;

class Ciclos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'ciclos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'numero',
        'data_inicio',
        'data_fim',
        'tipo',
        'situacao',
        'tanque_id',
        'filial_id',
        'usuario_id',
    ];

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function preparacao()
    {
        return $this->hasOne(Preparacoes::class, 'ciclo_id', 'id');
    }

    public function povoamento()
    {
        return $this->hasOne(Povoamentos::class, 'ciclo_id', 'id');
    }

    public function despescas()
    {
        return $this->hasMany(Despescas::class, 'ciclo_id', 'id');
    }

    public function estoque_saidas()
    {
        return $this->hasMany(VwEstoqueSaidas::class, 'ciclo_id', 'id');
    }

    public function analises_biometricas()
    {
        return $this->hasMany(AnalisesBiometricas::class, 'ciclo_id', 'id');
    }

    public function analises_biometricas_amostras()
    {

        return $this->hasMany(AnaliesBiometricasAmostras::class, 'analise_biometrica_id', 'id');
    }

    public function arracoamentos()
    {
        return $this->hasMany(Arracoamentos::class, 'ciclo_id', 'id');
    }

    public function analises_presuntivas()
    {
        return $this->hasMany(VwAnalisesPresuntivas::class, 'ciclo_id', 'id');
    }

    public function ciclo_peixes()
    {
        return $this->hasOne(VwCiclosPeixes::class, 'ciclo_id', 'id');
    }

    public function despesca(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $despescas = $this->despescas->where('data_inicio', '<=', $data_referencia . ' 23:59:59')
            ->sortByDesc('ordinal');

        if ($despescas->isNotEmpty()) {

            $primeira = $despescas->first();
            $ultima   = $despescas->last();

            $despesca = new Despescas;

            $despesca->data_inicio    = $primeira->data_inicio;
            $despesca->data_fim       = $ultima->data_fim;
            $despesca->qtd_prevista   = $despescas->sum('qtd_prevista');
            $despesca->qtd_despescada = $despescas->sum('qtd_despescada');
            $despesca->peso_medio     = $despescas->sum('peso_medio'); 

            $num_despescas = $despescas->count();

            if ($num_despescas != 0) {
                $despesca->peso_medio /= $num_despescas;
            }

            return $despesca;
        }

        return null;
    }

    public function biometrias_amostras(string $data_referencia = null, int $limit = 0)
    {
        $data_rferencia = $data_referencia ?: date('Y-m-d');
        $biometrias = $this->analises_biometricas
            ->where('data_analise', '<=', $data_referencia);
    }

    public function biometrias(string $data_referencia = null, int $limit = 0)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $biometrias = $this->analises_biometricas->where('data_analise', '<=', $data_referencia);

        if ($biometrias->isNotEmpty()) {

            if ($limit != 0) {
                return $biometrias->sortByDesc('data_analise')->take($limit);
            }

            return $biometrias->sortByDesc('data_analise');
        }

        $biometrias = collect();

        if ($this->tipo == 1) { // Cultivo de camarões

            $povoamento = $this->povoamento;

            if ($povoamento instanceof Povoamentos) {

                foreach ($povoamento->lotes as $lote) {
                    $biometrias->push($lote->lote->lote_biometria);
                }

                if ($biometrias->isNotEmpty()) {
                    return $biometrias;
                }
            }
        }

        return $biometrias;
    }


    public function crescimento_medio(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $biometrias   = $this->biometrias($data_referencia)->reverse();
        $num_analises = $biometrias->count();

        if ($num_analises != 0) {

            $crescimento             = [];
            $peso_atual              = 0;
            $peso_anterior           = 0;

            foreach ($biometrias as $biometria) {

                $peso_atual = $biometria->peso_medio();

                if ($peso_anterior == 0) {
                    $crescimento[] = $peso_atual;
                } else {
                    $crescimento[] = $peso_atual - $peso_anterior;
                }
                //dump([($peso_atual-$peso_anterior), $crescimento, $crescimento_acumulado]);

                $peso_anterior = $peso_atual;
            }

            //dd(array_sum($crescimento)/count($crescimento));
            $dias_cultivo = $this->dias_cultivo($data_referencia);


            $crescimento_medio = (($peso_atual / $dias_cultivo) * 7);

            //$crescimento_medio = (array_sum($crescimento)/count($crescimento));

            return $crescimento_medio;
        }

        return 0;
    }

    public function crescimento_2us(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $biometrias = $this->biometrias($data_referencia, 3);
        $num_analises = $biometrias->count();

        if ($num_analises == 3) {

            $peso = [];

            foreach ($biometrias as $biometria) {
                $peso[] = $biometria->peso_medio();
            }
            //dd($peso[1]);
            return ((($peso[0] - $peso[1]) + ($peso[1] - $peso[2])) / 2);
            //return ($peso[0] + $peso[1]) / 2;

        }

        return $this->crescimento_semanal();
    }

    public function crescimento_semanal(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $biometrias = $this->biometrias($data_referencia, 2);

        if ($biometrias->isNotEmpty()) {
            return ($biometrias->first()->peso_medio() - $biometrias->last()->peso_medio());
        }

        return 0;
    }

    public function crescimento_diario(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $biometrias = $this->biometrias($data_referencia, 2);

        $crescimento_semanal = $this->crescimento_semanal($data_referencia);

        if (!empty($biometrias) && !empty($crescimento_semanal)) {

            $intervalo_dias = Carbon::parse($biometrias->first()->data_analise)->diffInDays($biometrias->last()->data_analise);

            if ($intervalo_dias != 0) {
                return ($crescimento_semanal / $intervalo_dias);
            }
        }

        return 0;
    }

    public function sobrevivencia(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $despesca = $this->despesca($data_referencia);

        if ($despesca instanceof Despescas) {

            $povoamento = $this->povoamento;

            if ($povoamento instanceof Povoamentos) {

                $despesca->peso_medio = $despesca->peso_medio ?: 1;

                $qtd_animais = ($despesca->qtd_despescada * 1000) / $despesca->peso_medio;
                $qtd_poslarvas = $povoamento->qtd_poslarvas();

                if ($qtd_poslarvas != 0) {
                    return round((($qtd_animais / $qtd_poslarvas) * 100), 2);
                }
            }
        }
        $biometrias = $this->biometrias($data_referencia);

        if ($biometrias->isNotEmpty()) {
            return $biometrias->first()->sobrevivencia();
        }
        
        return 0;
        //dd($qtd_animais);
    }

    

    public function peso_medio(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $despesca = $this->despesca($data_referencia);

        if ($despesca instanceof Despescas) {
            return $despesca->peso_medio;
        }

        $biometrias = $this->biometrias($data_referencia);

        if ($biometrias->isNotEmpty()) {

            $peso_medio = $biometrias->first()->peso_medio();

            return $peso_medio;
        }

        return 0;
    }

    public function biomassa(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $despesca = $this->despesca($data_referencia);

        if ($despesca instanceof Despescas) {
            return $despesca->qtd_despescada;
        }

        $qtd_animais   = 0;
        $sobrevivencia = 80;
        $peso_medio    = $this->peso_medio($data_referencia);

        if ($this->tipo == 1) { // Cultivo de camarões

            $sobrevivencia = $this->sobrevivencia($data_referencia);

            $povoamento = $this->povoamento;

            if ($povoamento instanceof Povoamentos) {
                $qtd_animais = ($povoamento->qtd_poslarvas() / 1000); // Conversão da quantidade em unidades para milheiros
            }
        } else if ($this->tipo == 2) { // Cultivo de peixes

            $qtd_animais = $this->ciclo_peixes->qtd_peixes();
        }

        if (!empty($qtd_animais) && !empty($peso_medio)) {
            return (($sobrevivencia / 100) * $qtd_animais) * $peso_medio;
        }

        return 0;
    }

    public function racao_consumida(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $arracoamentos = $this->arracoamentos->where('data_aplicacao', '<=', $data_referencia);

        $racao_consumida = 0;

        foreach ($arracoamentos as $arracoamento) {
            $racao_consumida += $arracoamento->qtdTotalRacaoAplicada();
        }

        return $racao_consumida;
    }

    public function racao_consumida_ultimasemana(string $data_referencia = null)
    {


        $data_referencia = $data_referencia ?: date('Y-m-d');

        $data_referencia = Carbon::createFromFormat('Y-m-d', $data_referencia);

        $date_end = $data_referencia->format('Y-m-d');

        $date_begin = $data_referencia->subDays(7)->format('Y-m-d');

        //dd($date_end,$date_begin);

        $arracoamentos = $this->arracoamentos
            ->where('data_aplicacao', '<=', $date_end)
            ->where('data_aplicacao', '>=', $date_begin);


        $racao_consumida = 0;

        foreach ($arracoamentos as $arracoamento) {
            $racao_consumida += $arracoamento->qtdTotalRacaoAplicada();
        }
        return $racao_consumida;
        
    }

    public function genetica(string $data_referencia = null){
        $data_referencia = $data_referencia ?: date('Y-m-d');
        if ($this->tipo == 1) { // Cultivo de camarões

            $povoamento = $this->povoamento;

            if ($povoamento instanceof Povoamentos) {

                foreach ($povoamento->lotes as $lote) {
                    $genetica = $lote->lote->genetica;
                    return $genetica;
                }
            }
        }
    }

    //T.C.A Parcial.
    public function cons(string $data_referencia = null)
    {
            
        $data_referencia = $data_referencia ?: date('Y-m-d');
        $lastWeek = date('Y-m-d', strtotime($data_referencia. 'last sunday' . '-1 day'));

        $racao_consumida = $this->racao_consumida_ultimasemana($lastWeek);
        $biomassa = $this->biomassa();
        $bioSemAnterior = $this->biomassa($lastWeek);
        $qtd_poslarvas = $this->povoamento->qtd_poslarvas() / 1000;
	    $sobrevivenciaSemAnterior = $this->sobrevivencia($lastWeek) / 100;

	    $peso_medio = $this->peso_medio();
        $produtoSobrevivencia = ($sobrevivenciaSemAnterior * $peso_medio * $qtd_poslarvas) - $bioSemAnterior;

        $diferencaBioSem = (($biomassa + $sobrevivenciaSemAnterior) - ($bioSemAnterior + $sobrevivenciaSemAnterior));

        if (($diferencaBioSem!= 0) && ($racao_consumida != 0)) {

            return ($racao_consumida / $diferencaBioSem);
            //dd([$produtoSobrevivencia]);
        }

        return 0;
    }

    public function tca(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $biomassa        = $this->biomassa($data_referencia);
        $racao_consumida = $this->racao_consumida($data_referencia);

        if ($biomassa != 0) {
            return ($racao_consumida / $biomassa);
            //dd($racao_consumida);
        }

        return 0;
    }

    public function densidade(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $sobrevivencia = $this->sobrevivencia($data_referencia);
        $area          = $this->tanque->area;

        $povoamento = $this->povoamento;

        if ($povoamento instanceof Povoamentos) {

            $qtd_poslarvas = $povoamento->qtd_poslarvas();

            if (!empty($sobrevivencia) && !empty($qtd_poslarvas)) {
                return (($sobrevivencia / 100) * $qtd_poslarvas) / $area;
            }
        }

        return 0;
    }

    public function densidade_inicial()
    {
        $sobrevivencia = 100;
        $area          = $this->tanque->area;

        $povoamento = $this->povoamento;

        if ($povoamento instanceof Povoamentos) {

            $qtd_poslarvas = $povoamento->qtd_poslarvas();

            if (!empty($sobrevivencia) && !empty($qtd_poslarvas)) {
                return (($sobrevivencia / 100) * $qtd_poslarvas) / $area;
            }
        }

        return 0;
    }


    /*>>>>>> ***  ******  *********  ************ *************** <<<<<<*/

    public function fishingDays(string $data_referencia = null)
    {

        $data_referencia = $data_referencia ?: date('Y-m-d');

        if (!is_null($this->data_fim)) {

            $data_referencia = Carbon::createFromFormat('Y-m-d', $this->data_fim);
            $data_fim = Carbon::createFromFormat('Y-m-d', $this->data_fim);

            if ($data_referencia->greaterThan($data_fim)) {
                $data_referencia = $data_fim->format('Y-m-d');
            } else {
                $data_referencia = $data_referencia->format('Y-m-d');
            }
        }

        $dias_despesca = $this->dias_despesca;

        if ($dias_despesca instanceof Despescas) {
            return Carbon::parse($dias_despesca());
        }

        return 0;
    }

    /*>>>>>> ***  ******  *********  ************ *************** <<<<<<*/



    public function dias_atividade(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        if (!is_null($this->data_fim)) {

            $data_referencia = Carbon::createFromFormat('Y-m-d', $data_referencia);
            $data_fim = Carbon::createFromFormat('Y-m-d', $this->data_fim);

            if ($data_referencia->greaterThan($data_fim)) {
                $data_referencia = $data_fim->format('Y-m-d');
            } else {
                $data_referencia = $data_referencia->format('Y-m-d');
            }
        }
	
	if($this->numero == 1009){
	    return Carbon::parse($this->data_inicio)->diffInDays($data_referencia) +2;
	}	


        return Carbon::parse($this->data_inicio)->diffInDays($data_referencia) + $this->fishingDays($data_referencia) +1;
    }

    public function dias_cultivo(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        if (!is_null($this->data_fim)) {

            $data_referencia = Carbon::createFromFormat('Y-m-d', $data_referencia);
            $data_fim = Carbon::createFromFormat('Y-m-d', $this->data_fim);

            if ($data_referencia->greaterThan($data_fim)) {
                $data_referencia = $data_fim->format('Y-m-d');
            } else {
                $data_referencia = $data_referencia->format('Y-m-d');
            }
        }


        $povoamento = $this->povoamento;

        if ($povoamento instanceof Povoamentos) {
            return Carbon::parse($povoamento->data_fim('Y-m-d'))->diffInDays($data_referencia) + 1;
        }

        return 0;
    }

    public function dias_preparacao(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        if (!is_null($this->data_fim)) {

            $data_referencia = Carbon::createFromFormat('Y-m-d', $data_referencia);
            $data_fim = Carbon::createFromFormat('Y-m-d', $this->data_fim);

            if ($data_referencia->greaterThan($data_fim)) {
                $data_referencia = $data_fim->format('Y-m-d');
            } else {
                $data_referencia = $data_referencia->format('Y-m-d');
            }
        }

	if($this->numero == 1009){
	   return $this->dias_atividade($data_referencia) - $this->dias_cultivo($data_referencia);
       }

        return $this->dias_atividade($data_referencia) - $this->dias_cultivo($data_referencia);
    }

    public function saidas_totais(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $saidas_totais = VwEstoqueSaidas::totaisPorCiclo($this->id, $data_referencia);

        return $saidas_totais;
    }

    public function consumosRecebidos(string $data_inicial, string $data_final)
    {
        $consumos_recebidos = DB::select('select * from f_consumos_recebidos(?, ?, ?, ?)', [
            $data_inicial, $data_final, $this->id, $this->filial_id
        ]);

        $consumos = [];

        foreach ($consumos_recebidos as $consumo) {
            $consumos[$consumo->tanque_tipo_id][$consumo->produto_id] = $consumo->quantidade;
        }

        return $consumos;
    }

    public function custo_rateio(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $custo_rateio = DB::selectOne('select f_custo_rateio(?, ?, ?, ?) as valor_total', [
            $this->data_inicio, $data_referencia, $this->id, $this->filial_id
        ]);

        return $custo_rateio->valor_total;
    }

    public function custo_saidas(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');
    
            $custo_saidas = DB::selectOne('select f_custo_saidas(?, ?) as valor_total', [
                $data_referencia, $this->id
            ]);

            if(!is_null($custo_saidas->valor_total)){

            return $custo_saidas->valor_total;
        }

        return 0;
    }

    public function custo_fixado(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $custos_fixados = DB::selectOne('select * from f_custo_fixado(?, ?, ?)', [
            $data_referencia, $this->id, $this->filial_id
        ]);

        return $custos_fixados;
    }

    public function custo_total(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $custo_fixado = $this->custo_fixado($data_referencia);
        $custo_total  = $this->custo_saidas($data_referencia);
        $custo_total += $this->custo_rateio($data_referencia);
        $custo_total += $custo_fixado->total;

        return $custo_total;
    }

    public function custo_kg(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $custo_total  = $this->custo_total($data_referencia);

        $biomassa = $this->biomassa($data_referencia);

        if ($biomassa != 0) {
            return ($custo_total / $biomassa);
        }

        return 0;
    }

    public function kg_hectare(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        $area     = $this->tanque->hectares();
        $biomassa = $this->biomassa($data_referencia);

        if ($biomassa != 0) {
            return ($biomassa / $area);
        }

        return 0;
    }

    public function data_inicio(string $format = 'd/m/Y')
    {
        if (!$this->data_inicio) {
            return $this->data_inicio;
        }

        return Carbon::parse($this->data_inicio)->format($format);
    }

    public function data_fim(string $format = 'd/m/Y')
    {
        if (!$this->data_fim) {
            return $this->data_fim;
        }

        return Carbon::parse($this->data_fim)->format($format);
    }

    public function situacao($situacao = null)
    {
        $situacoes = [
            1 => 'Vazio',
            2 => 'Em limpeza',
            3 => 'Em abastecimento',
            4 => 'Em fertilização',
            5 => 'Em povoamento',
            6 => 'Em engorda',
            7 => 'Em despesca',
            8 => 'Encerrado',
        ];

        if (!$situacao) {
            $situacao = $this->situacao;
        }

        return $situacoes[$situacao];
    }

    public function tipo()
    {
        $tipos = [
            1 => 'Cultivo de camarões',
            2 => 'Cultivo de peixes',
        ];

        if (!empty($this->getAttributes())) {
            return $tipos[$this->tipo];
        }

        return $tipos;
    }

    public function verificarSituacao(array $situacoes)
    {
        foreach ($situacoes as $situacao) {
            if ($situacao == $this->situacao) {
                return true;
            }
        }

        return false;
    }

    public function sucedeDataInicio(string $data)
    {
        $data = Carbon::parse($data)->format('Y-m-d');

        return (strtotime($this->data_inicio) <= strtotime($data));
    }

    public function arracoamentosTodosValidados()
    {
        $naoValidados = $this->arracoamentos->where('situacao', '<>', 'V');

        if ($naoValidados->count() == 0) {
            return true;
        }

        return false;
    }

    public static function search(array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

            if (isset($data['tanque_id'])) {
                $query->where('tanque_id', $data['tanque_id']);
            }

            if (isset($data['numero'])) {
                $query->where('numero', $data['numero']);
            }

            if (isset($data['data_inicio'])) {
                $data['data_inicio'] = Carbon::createFromFormat('d/m/Y', $data['data_inicio'])->format('Y-m-d');
            }

            if (isset($data['data_fim'])) {
                $data['data_fim'] = Carbon::createFromFormat('d/m/Y', $data['data_fim'])->format('Y-m-d');
            }

            if (isset($data['data_inicio']) && isset($data['data_fim'])) {

                $query->where(function ($query) use ($data) {
                    $query->whereBetween('data_inicio', [$data['data_inicio'], $data['data_fim']]);
                    $query->orWhereBetween('data_fim',  [$data['data_inicio'], $data['data_fim']]);
                });
            } elseif (isset($data['data_inicio'])) {

                $query->where('data_inicio', $data['data_inicio']);
            } elseif (isset($data['data_fim'])) {

                $query->where('data_fim', $data['data_fim']);
            }
        })
            ->orderBy('data_inicio', 'desc')
            ->orderBy('id',          'desc')
            ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }
        })
            ->orderBy('data_inicio', 'desc')
            ->orderBy('id',          'desc')
            ->paginate($rowsPerPage);
    }
}
