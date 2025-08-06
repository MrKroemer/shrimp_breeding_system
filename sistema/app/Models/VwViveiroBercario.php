<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwViveiroBercario extends Model
{
    protected $table = 'vw_viveiro_bercario';
    protected $primaryKey = 'id';


    public function ciclos()
    {
        return $this->hasMany(Ciclos::class, 'tanque_id', 'id');
    }
}
