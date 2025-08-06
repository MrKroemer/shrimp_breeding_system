<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GruposFiliais extends Model
{
    public $timestamps = false;

    protected $table = 'grupos_filiais';
    protected $primaryKey = 'id';
    protected $fillable = [
        'grupo_id',
        'filial_id',
        'situacao',
    ];

    public function permissoes()
    {
        return $this->hasMany(GruposPermissoes::class, 'grupo_filial_id', 'id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupos::class, 'grupo_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

            if (isset($data['grupo_id'])) {
                $query->where('grupo_id', $data['grupo_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['grupo_id'])) {
                $query->where('grupo_id', $data['grupo_id']);
            }
            
        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
