<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuariosFiliais extends Model
{
    public $timestamps = false;

    protected $table = 'usuarios_filiais';
    protected $primaryKey = 'id';
    protected $fillable = [
        'usuario_id',
        'filial_id',
        'situacao',
    ];

    public function permissoes()
    {
        return $this->hasMany(UsuariosPermissoes::class, 'usuario_filial_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
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

            if (isset($data['usuario_id'])) {
                $query->where('usuario_id', $data['usuario_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['usuario_id'])) {
                $query->where('usuario_id', $data['usuario_id']);
            }
            
        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
