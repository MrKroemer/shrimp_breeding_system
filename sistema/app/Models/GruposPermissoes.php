<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GruposPermissoes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'grupos_permissoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'grupo_filial_id',
        'modulo_id',
        'menu_id',
        'permissoes'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulos::class, 'modulo_id', 'id');
    }
    
    public function menu()
    {
        return $this->belongsTo(Menus::class, 'menu_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['menu'])) {

                $menus = Menus::where('nome', $data['menu'])->get();

                if ($menus->count() > 0) {
                    foreach ($menus as $menu) {
                        $query->where('menu_id', $menu->id);
                    }
                } else {
                    $query->where('menu_id', 0);
                }

            }

            if (isset($data['grupo_filial_id'])) {
                $query->where('grupo_filial_id', $data['grupo_filial_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['grupo_filial_id'])) {
                $query->where('grupo_filial_id', $data['grupo_filial_id']);
            }
        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
