<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresuntivasBranquias extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'presuntivas_branquias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'presuntiva_id',
        'epistilis',
        'zoothamium',
        'acinetos',
        'necroses',
        'melanoses',
        'deformidade',
        'leucotrix',
        'biofloco',
    ];
    
    public function presuntiva()
    {
        return $this->belongsTo(Presuntiva::class, 'presuntiva_id', 'id');
    }

}
