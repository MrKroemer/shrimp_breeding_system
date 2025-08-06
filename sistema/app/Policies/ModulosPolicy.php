<?php

namespace App\Policies;

use App\Models\Usuarios;
use App\Models\Modulos;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModulosPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the modulo.
     *
     * @param  \App\Models\Usuarios  $user
     * @param  \App\Models\Modulos  $modulo
     * @return mixed
     */
    public function view(Usuarios $user, Modulos $modulo)
    {
        //
    }

    /**
     * Determine whether the user can create modulos.
     *
     * @param  \App\Models\Usuarios  $user
     * @return mixed
     */
    public function create(Usuarios $user)
    {
        //
    }

    /**
     * Determine whether the user can update the modulo.
     *
     * @param  \App\Models\Usuarios  $user
     * @param  \App\Models\Modulos  $modulo
     * @return mixed
     */
    public function update(Usuarios $user, Modulos $modulo)
    {
        //
    }

    /**
     * Determine whether the user can delete the modulo.
     *
     * @param  \App\Models\Usuarios  $user
     * @param  \App\Models\Modulos  $modulo
     * @return mixed
     */
    public function delete(Usuarios $user, Modulos $modulo)
    {
        //
    }
}
