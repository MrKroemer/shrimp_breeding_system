<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AnalisesBiometricas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'analises_biometricas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_analise',
        'total_animais',
        'peso_total',
        'peso_medio',
        'sobrevivencia',
        'situacao',
        'ciclo_id',
        'filial_id',
        'usuario_id',
    ];

    public $classificacoes = [
        'classe330to1000' => [0.0,  2.9],
        'classe200to330'  => [3.0,  5.5],
        'classe150to200'  => [5.6,  6.6],
        'classe120to150'  => [6.7,  8.2],
        'classe100to120'  => [8.3,  9.9],
        'classe80to100'   => [10,   12.4],
        'classe70to80'    => [12.5, 14.2],
        'classe60to70'    => [14.3, 16.6],
        'classe50to60'    => [16.7, 19.9],
        'classe40to50'    => [20,   24.9],
        'classe30to40'    => [25.0, 33.2],
        'classe20to30'    => [33.3, 49.9],
        'classe10to20'    => [50,   99.9],
        'classe0to10'     => [100,  200],
    ];

    public $qualificacoes = [
        'opaco'         => 1,
        'deformado'     => 2,
        'caudavermelha' => 3,
        'flacido'       => 4,
        'mole'          => 5,
        'semimole'      => 6,
        'necrose1'      => 7,
        'necrose2'      => 8,
        'necrose3'      => 9,
        'necrose4'      => 10,
        'total'         => 11,
    ];

    public $gramaturas = [
        '_01'  => 0.1,
        '_02'  => 0.2,
        '_03'  => 0.3,
        '_04'  => 0.4,
        '_05'  => 0.5,
        '_06'  => 0.6,
        '_07'  => 0.7,
        '_08'  => 0.8,
        '_09'  => 0.9,
        '_10'  => 1.0,
        '_11'  => 1.1,
        '_12'  => 1.2,
        '_13'  => 1.3,
        '_14'  => 1.4,
        '_15'  => 1.5,
        '_16'  => 1.6,
        '_17'  => 1.7,
        '_18'  => 1.8,
        '_19'  => 1.9,
        '_20'  => 2.0,
        '_21'  => 2.1,
        '_22'  => 2.2,
        '_23'  => 2.3,
        '_24'  => 2.4,
        '_25'  => 2.5,
        '_26'  => 2.6,
        '_27'  => 2.7,
        '_28'  => 2.8,
        '_29'  => 2.9,
        '_30'  => 3.0,
        '_31'  => 3.1,
        '_32'  => 3.2,
        '_33'  => 3.3,
        '_34'  => 3.4,
        '_35'  => 3.5,
        '_36'  => 3.6,
        '_37'  => 3.7,
        '_38'  => 3.8,
        '_39'  => 3.9,
        '_40'  => 4.0,
        '_41'  => 4.1,
        '_42'  => 4.2,
        '_43'  => 4.3,
        '_44'  => 4.4,
        '_45'  => 4.5,
        '_46'  => 4.6,
        '_47'  => 4.7,
        '_48'  => 4.8,
        '_49'  => 4.9,
        '_50'  => 5.0,
        '_51'  => 5.1,
        '_52'  => 5.2,
        '_53'  => 5.3,
        '_54'  => 5.4,
        '_55'  => 5.5,
        '_56'  => 5.6,
        '_57'  => 5.7,
        '_58'  => 5.8,
        '_59'  => 5.9,
        '_60'  => 6.0,
        '_61'  => 6.1,
        '_62'  => 6.2,
        '_63'  => 6.3,
        '_64'  => 6.4,
        '_65'  => 6.5,
        '_66'  => 6.6,
        '_67'  => 6.7,
        '_68'  => 6.8,
        '_69'  => 6.9,
        '_70'  => 7.0,
        '_71'  => 7.1,
        '_72'  => 7.2,
        '_73'  => 7.3,
        '_74'  => 7.4,
        '_75'  => 7.5,
        '_76'  => 7.6,
        '_77'  => 7.7,
        '_78'  => 7.8,
        '_79'  => 7.9,
        '_80'  => 8.0,
        '_81'  => 8.1,
        '_82'  => 8.2,
        '_83'  => 8.3,
        '_84'  => 8.4,
        '_85'  => 8.5,
        '_86'  => 8.6,
        '_87'  => 8.7,
        '_88'  => 8.8,
        '_89'  => 8.9,
        '_90'  => 9.0,
        '_91'  => 9.1,
        '_92'  => 9.2,
        '_93'  => 9.3,
        '_94'  => 9.4,
        '_95'  => 9.5,
        '_96'  => 9.6,
        '_97'  => 9.7,
        '_98'  => 9.8,
        '_99'  => 9.9,
        '_100' => 10.0,
        '_101' => 10.1,
        '_102' => 10.2,
        '_103' => 10.3,
        '_104' => 10.4,
        '_105' => 10.5,
        '_106' => 10.6,
        '_107' => 10.7,
        '_108' => 10.8,
        '_109' => 10.9,
        '_110' => 11.0,
        '_111' => 11.1,
        '_112' => 11.2,
        '_113' => 11.3,
        '_114' => 11.4,
        '_115' => 11.5,
        '_116' => 11.6,
        '_117' => 11.7,
        '_118' => 11.8,
        '_119' => 11.9,
        '_120' => 12.0,
        '_121' => 12.1,
        '_122' => 12.2,
        '_123' => 12.3,
        '_124' => 12.4,
        '_125' => 12.5,
        '_126' => 12.6,
        '_127' => 12.7,
        '_128' => 12.8,
        '_129' => 12.9,
        '_130' => 13.0,
        '_131' => 13.1,
        '_132' => 13.2,
        '_133' => 13.3,
        '_134' => 13.4,
        '_135' => 13.5,
        '_136' => 13.6,
        '_137' => 13.7,
        '_138' => 13.8,
        '_139' => 13.9,
        '_140' => 14.0,
        '_141' => 14.1,
        '_142' => 14.2,
        '_143' => 14.3,
        '_144' => 14.4,
        '_145' => 14.5,
        '_146' => 14.6,
        '_147' => 14.7,
        '_148' => 14.8,
        '_149' => 14.9,
        '_150' => 15.0,
        '_151' => 15.1,
        '_152' => 15.2,
        '_153' => 15.3,
        '_154' => 15.4,
        '_155' => 15.5,
        '_156' => 15.6,
        '_157' => 15.7,
        '_158' => 15.8,
        '_159' => 15.9,
        '_160' => 16.0,
        '_161' => 16.1,
        '_162' => 16.2,
        '_163' => 16.3,
        '_164' => 16.4,
        '_165' => 16.5,
        '_166' => 16.6,
        '_167' => 16.7,
        '_168' => 16.8,
        '_169' => 16.9,
        '_170' => 17.0,
        '_171' => 17.1,
        '_172' => 17.2,
        '_173' => 17.3,
        '_174' => 17.4,
        '_175' => 17.5,
        '_176' => 17.6,
        '_177' => 17.7,
        '_178' => 17.8,
        '_179' => 17.9,
        '_180' => 18.0,
        '_181' => 18.1,
        '_182' => 18.2,
        '_183' => 18.3,
        '_184' => 18.4,
        '_185' => 18.5,
        '_186' => 18.6,
        '_187' => 18.7,
        '_188' => 18.8,
        '_189' => 18.9,
        '_190' => 19.0,
        '_191' => 19.1,
        '_192' => 19.2,
        '_193' => 19.3,
        '_194' => 19.4,
        '_195' => 19.5,
        '_196' => 19.6,
        '_197' => 19.7,
        '_198' => 19.8,
        '_199' => 19.9,
        '_200' => 20.0,
        '_201' => 20.1,
        '_202' => 20.2,
        '_203' => 20.3,
        '_204' => 20.4,
        '_205' => 20.5,
        '_206' => 20.6,
        '_207' => 20.7,
        '_208' => 20.8,
        '_209' => 20.9,
        '_210' => 21.0,
        '_211' => 21.1,
        '_212' => 21.2,
        '_213' => 21.3,
        '_214' => 21.4,
        '_215' => 21.5,
        '_216' => 21.6,
        '_217' => 21.7,
        '_218' => 21.8,
        '_219' => 21.9,
        '_220' => 22.0,
        '_221' => 22.1,
        '_222' => 22.2,
        '_223' => 22.3,
        '_224' => 22.4,
        '_225' => 22.5,
        '_226' => 22.6,
        '_227' => 22.7,
        '_228' => 22.8,
        '_229' => 22.9,
        '_230' => 23.0,
        '_231' => 23.1,
        '_232' => 23.2,
        '_233' => 23.3,
        '_234' => 23.4,
        '_235' => 23.5,
        '_236' => 23.6,
        '_237' => 23.7,
        '_238' => 23.8,
        '_239' => 23.9,
        '_240' => 24.0,
        '_241' => 24.1,
        '_242' => 24.2,
        '_243' => 24.3,
        '_244' => 24.4,
        '_245' => 24.5,
        '_246' => 24.6,
        '_247' => 24.7,
        '_248' => 24.8,
        '_249' => 24.9,
        '_250' => 25.0,
        '_251' => 25.1,
        '_252' => 25.2,
        '_253' => 25.3,
        '_254' => 25.4,
        '_255' => 25.5,
        '_256' => 25.6,
        '_257' => 25.7,
        '_258' => 25.8,
        '_259' => 25.9,
        '_260' => 26.0,
    ];

    public function data_analise(String $format = 'd/m/Y')
    {
        if (! $this->data_analise) {
            return $this->data_analise;
        }

        return Carbon::parse($this->data_analise)->format($format);
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function amostras()
    {
        return $this->hasMany(AnalisesBiometricasAmostras::class, 'analise_biometrica_id', 'id');
    }

    public function ultimas_amostras()
    {
        $amostras = $this->amostras->sortByDesc('id')->take(10);

        return $amostras;
    }

    public function classificacoes()
    {
        $classificacoes = [];

        foreach ($this->classificacoes as $classificacao => $intervalo) {

            $classificacoes[$classificacao] = $this->amostras
            ->where('gramatura', '>=', $intervalo[0])
            ->where('gramatura', '<=', $intervalo[1])
            ->count();

        }

        return $classificacoes;
    }

    public function qualificacoes()
    {
        $qualificacoes = [];

        foreach ($this->qualificacoes as $qualificacao => $valor) {
            $qualificacoes[$qualificacao] = $this->amostras->where('qualificacao', $valor)->count();
        }

        $qualificacoes['total'] =  $this->amostras->count();

        return $qualificacoes;
    }

    public function coeficiente_variacao()
    {
        $coeficiente = number_format(($this->desvio_padrao()/$this->peso_medio())*100, 1, '.', ',');

        return $coeficiente;
    }

    public function classe_variacao(float $classe = 0.0)
    {
        if (isset($classe)) {

            $classes = [
                'classes' => [($classe - 0.2), ($classe + 0.2)]
            ];

        } else {

            $classes = [
                'classes' => [0.1, 1.7]
            ];

        }

        $contagem = $this->amostras
        ->where('gramatura', '>=', $classes['classes'][0])
        ->where('gramatura', '<=', $classes['classes'][1])
        ->count();

        return $contagem;
    }

    public function total_animais()
    {
        $total_animais = (int) $this->total_animais;

        if (empty($total_animais)) {
            $total_animais = $this->amostras->count();
        }

        return $total_animais;
    }

    public function peso_total()
    {
        $peso_total = (float) $this->peso_total;

        if (empty($peso_total)) {
            $peso_total = $this->amostras->sum('gramatura');
        }

        return $peso_total;
    }

    public function peso_medio()
    {
        $peso_medio = (float) $this->peso_medio;

        if (empty($peso_medio) && $this->total_animais() > 0) {
            $peso_medio = ($this->peso_total() / $this->total_animais());
        }

        return $peso_medio;
    }

    public function situacao($situacao = null)
    {
        $situacoes = [
            1 => 'Em Coleta', 
            2 => 'Coleta Encerrada', 
            3 => 'Finalizado', 
           
        ];

        if (! $situacao) {
            $situacao = $this->situacao;
        }

        return $situacoes[$situacao];
    }

    public function sobrevivencia()
    {
        $sobrevivencia = (float) $this->sobrevivencia;

        if (empty($sobrevivencia)) {
            
            $analise_biometrica = static::where('ciclo_id', $this->ciclo_id)
            ->whereNotNull('sobrevivencia')
            ->orderBy('data_analise', 'desc')
            ->first();

            if ($analise_biometrica instanceof static) {
                $sobrevivencia = (float) $analise_biometrica->sobrevivencia;
            }

        }

        return $sobrevivencia;
    }

    public function desvio_padrao()
    {
        $variancia = 0;
        $desvio_padrao = 0;
        $peso_medio = $this->peso_medio();
        $amostras   = $this->amostras;
        
        foreach($amostras as $amostra) {
            $variancia = ($variancia + (($amostra->gramatura - $peso_medio) * ($amostra->gramatura - $peso_medio)));
        }

        if(($variancia)&&($amostras->count())) {
            $desvio_padrao = sqrt($variancia / $amostras->count());
        }

        return number_format($desvio_padrao, 2, '.', ',');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['data_analise'])) {
                $data['data_analise'] = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-m-d');
                $query->where('data_analise', $data['data_analise']);
            }

            if (isset($data['ciclo_id'])) {
                $query->where('ciclo_id', $data['ciclo_id']);
            }

        })
        ->orderBy('data_analise', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('data_analise', 'desc')
        ->paginate($rowsPerPage);
    }
}
