<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresuntivasMacroscopica extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'presuntivas_macroscopicas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'presuntiva_id',
        'cor_animal',
        'edema_uropodos',
        'aparencia_musculatura',
        'aparencia_carapaca',
        'antenas',
        'total_cor_animal',
        'total_antenas',
        'total_aparencia_musculatura',
        'total_aparencia_carapaca',
    ];
    
    public function presuntiva()
    {
        return $this->belongsTo(Presuntiva::class, 'presuntiva_id', 'id');
    }
}
