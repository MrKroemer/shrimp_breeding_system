<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TanquesTipos;

class Setores extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'setores';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'sigla',
        'tipo',
        'filial_id',
    ];

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function subsetores()
    {
        return $this->hasMany(Subsetores::class, 'setor_id', 'id');
    }

    public function amostras()
    {
        return $this->hasMany(VwColetasParametrosPorTanque::class, 'setor_id', 'id');
    }

    public function tanques()
    {
        return $this->hasMany(Tanques::class, 'setor_id', 'id');
    }

    public function viveiros_bercarios()
    {
        return $this->hasMany(VwViveirosBercarios::class, 'setor_id', 'id');
    }

    public function tipo()
    {
        $tipos = [

            1 => 'Produção de camarões', 
            2 => 'Produção de peixes',
        ];

        if (! empty($this->getAttributes())) {
            return $tipos[$this->tipo];
            
        }
            return $tipos;
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['nome'])) {
                $query->where('nome', 'LIKE', "%{$data['nome']}%");
            }

            if (isset($data['sigla'])) {
                $query->where('sigla', $data['sigla']);
            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('nome')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('nome')
        ->paginate($rowsPerPage);
    }
}
