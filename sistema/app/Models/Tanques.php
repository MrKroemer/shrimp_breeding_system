<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tanques extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'tanques';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'sigla',
        'area',
        'altura',
        'volume',
        'nivel',
        'situacao',
        'tanque_tipo_id',
        'filial_id',
        'setor_id',
        'usuario_id',
    ];

    public function id()
    {
        return $this->id;
    }

    public function sigla()
    {
        return $this->sigla;
    }

    public function tanque_tipo()
    {
        return $this->belongsTo(TanquesTipos::class, 'tanque_tipo_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function setor()
    {
        return $this->belongsTo(Setores::class, 'setor_id', 'id');
    }

    public function aplicacao_insumo_grupo()
    {
        return $this->hasOne(VwAplicacoesInsumosGruposTanques::class, 'tanque_id', 'id');
    }

    public function amostras()
    {
        return $this->hasMany(VwColetasParametrosPorTanque::class, 'tnq_id', 'id');
    }

    public function coletas_parametros_amostras()
    {
        return $this->hasMany(ColetasParametrosAmostrasNew::class, 'tanque_id', 'id');
    }

    public function ciclos()
    {
        return $this->hasMany(Ciclos::class, 'tanque_id', 'id');
    }

    public function aplicacoes_insumos()
    {
        return $this->hasMany(AplicacoesInsumos::class, 'tanque_id', 'id');
    }

    public function saidas_avulsas()
    {
        return $this->hasMany(SaidasAvulsas::class, 'tanque_id', 'id');
    }

    public function estoque_saidas()
    {
        return $this->hasMany(VwEstoqueSaidas::class, 'tanque_id', 'id');
    }

    public function grupos_rateios()
    {
        return $this->hasMany(VwGruposRateiosTanques::class, 'tanque_id', 'id');
    }

    public function transferencias_peixes()
    {
        return $this->hasMany(TransferenciasPeixes::class, 'tanque_id', 'id');
    }

    public function ultima_transferencia()
    {
        $transferencias = $this->transferencias_peixes
        ->sortByDesc('data_movimento')
        ->sortByDesc('id');

        foreach ($transferencias as $transferencia) {

            if ($transferencia instanceof TransferenciasPeixes) {

                $ultima_transferencia_l = $transferencia->lote_peixes->ultima_transferencia();

                if ($transferencia->id == $ultima_transferencia_l->id) {
                    return $transferencia;
                }

            }

        }

        return null;
    }

    public function lotes_peixes()
    {
        $lotes = [];

        $transferencias = $this->transferencias_peixes
        ->sortByDesc('data_movimento')
        ->sortByDesc('id');

        foreach ($transferencias as $transferencia) {

            if ($transferencia instanceof TransferenciasPeixes) {

                $ultima_transferencia_l = $transferencia->lote_peixes->ultima_transferencia();

                if ($transferencia->id == $ultima_transferencia_l->id) {
                    $lotes[$transferencia->lote_peixes_id] = $transferencia->lote_peixes;
                }

            }

        }

        return collect($lotes);
    }

    public function ultimo_ciclo()
    {
        return $this->ciclos->sortByDesc('id')->first();
    }

    public function hectares()
    {
        return ($this->area / 10000);
    }

    public static function tanquesAtivos()
    {
        return static::where('tanques.filial_id', session('_filial')->id)
        ->where('tanques.situacao', 'ON');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['nome'])) {
                $data['nome'] = mb_strtoupper($data['nome']);
                $query->where('nome', 'LIKE', "%{$data['nome']}%");
            }

            if (isset($data['sigla'])) {
                $data['sigla'] = mb_strtoupper($data['sigla']);
                $query->where('sigla', 'LIKE', "%{$data['sigla']}%");
            }

        })
        ->orderBy('sigla')
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('sigla')
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }
}
