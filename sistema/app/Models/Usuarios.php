<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

class Usuarios extends Authenticatable
{
    use Notifiable;

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'email', 
        'username', 
        'password', 
        'imagem',
        'situacao',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function filiais()
    {
        return $this->hasMany(UsuariosFiliais::class, 'usuario_id', 'id');
    }

    public function grupos()
    {
        return $this->hasMany(GruposUsuarios::class, 'usuario_id', 'id');
    }

    // Método deve ser removido
    public function permissoes()
    {
        return $this->hasMany(UsuariosPermissoes::class, 'usuario_filial_id', 'id');
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

            if (isset($data['username'])) {
                $query->where('username', $data['username']);
            }

        })
        ->orderBy('nome')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            // 
        })
        ->orderBy('nome')
        ->paginate($rowsPerPage);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
