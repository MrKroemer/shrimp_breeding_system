<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SondasLaboratoriais extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'sondas_laboratoriais';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'marca',
        'numero_serie',
        'situacao',
        'usuario_id',
        'filial_id',
    ];

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
    }

    public function coletas_parametros()
    {
        return $this->hasMany(ColetasParametrosNew::class, 'sonda_laboratorial_id', 'id');
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

            if (isset($data['marca'])) {
                $query->where('marca', 'LIKE', "%{$data['marca']}%");
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
