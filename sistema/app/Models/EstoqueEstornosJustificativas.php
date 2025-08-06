<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EstoqueEstornosJustificativas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';
    
    protected $table = 'estoque_estornos_justificativas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descricao',
        'tipo_origem',
        'filial_id',
        'usuario_id',
    ];

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
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

    public function alterado_em(String $format = 'd/m/Y')
    {
        if (! $this->alterado_em) {
            return $this->alterado_em;
        }

        return Carbon::parse($this->alterado_em)->format($format);
    }

    public static function search(Array $data = null, int $rowsPerPage = null)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);
            
            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['data_inicial']) && isset($data['data_final'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d 00:00:00');
                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d 23:59:59');

                $query->whereBetween('alterado_em', [$data['data_inicial'], $data['data_final']]);

            } elseif (isset($data['data_inicial'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d 00:00:00');

                $query->where('alterado_em', '>=', $data['data_inicial']);

            } elseif (isset($data['data_final'])) {

                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d 23:59:59');

                $query->where('alterado_em', '<=', $data['data_final']);

            }

            if (isset($data['tipo_origem'])) {
                $query->where('tipo_origem', $data['tipo_origem']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
