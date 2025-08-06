<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'menus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'tipo',
        'rota',
        'icone',
        'situacao',
        'menu_id',
        'modulo_id',
    ];

    public function menu()
    {
        return $this->belongsTo(Menus::class, 'menu_id', 'id');
    }

    public function submenus()
    {
        return $this->hasMany(Menus::class, 'menu_id', 'id');
    }

    public function tipo($tipo = null)
    {
        $tipos = [
            'menu'     => 'Opção de menu',
            'submenu'  => 'Item de submenu',
        ];

        if (!$tipo) {
            return $tipos;
        }

        return $tipos[$tipo];
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

            if (isset($data['menu_id'])) {
                $query->where('menu_id', $data['menu_id']);
                $query->where('tipo', 'submenu');
            } else {
                $query->where('tipo', 'menu');
            }

            if (isset($data['modulo_id'])) {
                $query->where('modulo_id', $data['modulo_id']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (
                isset($data['menu_id']) && 
                isset($data['tipo']) && 
                $data['tipo'] == 'submenu'
            ) {

                $query->where('menu_id', $data['menu_id']);
                $query->where('tipo',    $data['tipo']);

            } elseif (
                isset($data['tipo']) && 
                $data['tipo'] == 'menu'
            ) {

                $query->where('tipo', 'menu');
                
            }

            if (isset($data['modulo_id'])) {
                $query->where('modulo_id', $data['modulo_id']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }
}
