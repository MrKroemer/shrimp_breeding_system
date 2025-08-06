<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Setorestipo extends Model
{

const CREATED_AT = 'criado_em';
const UPDATED_AT =  'alterado_em';

protected $table = 'setores_tipo';
protected $primaryKey = 'id';
protected $fillable = [
    'nome'
];


public function setores(){
    return $this->hasMany(Setores::class, 'setores_id', 'id');
}












}