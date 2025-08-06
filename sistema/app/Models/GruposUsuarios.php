<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GruposUsuarios extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'grupos_usuarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'grupo_id',
        'usuario_id',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupos::class, 'grupo_id', 'id');
    }
    
    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['usuario_id'])) {
                $query->where('usuario_id', $data['usuario_id']);
            }

            if (isset($data['grupo_id'])) {
                $query->where('grupo_id', $data['grupo_id']);
            }

            if (isset($data['usuario'])) {

                $usuarios = Usuarios::where('nome', $data['usuario'])->get();

                if ($usuarios->count() > 0) {
                    foreach ($usuarios as $usuario) {
                        $query->where('usuario_id', $usuario->id);
                    }
                } else {
                    $query->where('usuario_id', 0);
                }

            }

            if (isset($data['grupo'])) {

                $grupos = Grupos::where('nome', $data['grupo'])->get();

                if ($grupos->count() > 0) {
                    foreach ($grupos as $grupo) {
                        $query->where('grupo_id', $grupo->id);
                    }
                } else {
                    $query->where('grupo_id', 0);
                }

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

            if (isset($data['grupo_id'])) {
                $query->where('grupo_id', $data['grupo_id']);
            }
            
        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
