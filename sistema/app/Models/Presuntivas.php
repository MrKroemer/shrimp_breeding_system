<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PresuntivasBranquias;
use App\Models\PresuntivasHepatopancreas;
use App\Models\PresuntivasMacroscopica;
use App\Models\PresuntivasInformacoesComplementares;
use Carbon\Carbon;

class Presuntivas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'presuntivas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ciclo_id',
        'usuario_id',
        'filial_id',
        'data_analise',
        'data_povoamento',
        'densidade',
        'dias_cultivo',
        'gramatura',
    ];

    public function presuntivasBranquias()
    {
        return $this->hasOne(PresuntivasBranquias::class, 'presuntiva_id', 'id');
        //return PresuntivasBranquias::where('presuntiva_id', $this->id)->first();
    }

    public function presuntivasMacroscopicas()
    {
        return $this->hasOne(PresuntivasMacroscopica::class, 'presuntiva_id', 'id');
        //return PresuntivasMacroscopica::where('presuntiva_id', $this->id)->first();
    }

    public function presuntivasHepatopancreas()
    {
        return $this->hasOne(PresuntivasHepatopancreas::class, 'presuntiva_id', 'id');
        //return PresuntivasHepatopancreas::where('presuntiva_id', $this->id)->first();
    }

    public function presuntivasInformacoesComplementares()
    {
        return $this->hasOne(PresuntivasInformacoesComplementares::class, 'presuntiva_id', 'id');
        //return PresuntivasInformacoesComplementares::where('presuntiva_id', $this->id)->first();
    }

    public function data_analise()
    {
        return date('d/m/Y', strtotime($this->data_analise));
    }

    public function data_analise2()
    {
        return date('Y-m-d', strtotime($this->data_analise));
    }

    public function data_povoamento()
    {
        return date('d/m/Y', strtotime($this->data_povoamento));
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {
		$query->where('filial_id', session('_filial')->id);	

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['data_analise'])) {
                $data['data_analise'] = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-m-d');

                $query->where('data_analise', $data['data_analise']);
            }

        })
        ->orderByDesc('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderByDesc('id')
        ->paginate($rowsPerPage);
    }
}
