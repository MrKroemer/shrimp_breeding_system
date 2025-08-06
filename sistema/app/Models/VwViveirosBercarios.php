<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwViveirosBercarios extends Model
{
    protected $table = 'vw_viveiros_bercarios';

    public function ciclos()
    {
        return $this->hasMany(Ciclos::class, 'tanque_id', 'id');
    }
}
