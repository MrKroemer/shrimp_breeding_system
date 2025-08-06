<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwMortalidade extends Model
{
    protected $table = 'vw_mortalidade';
    protected $primaryKey = 'arracoamento_id';

    public function ciclo(){

        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }
}
