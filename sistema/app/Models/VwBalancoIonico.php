<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwBalancoIonico extends Model
{
    protected $table = 'vw_balanco_ionico';


    public function data_coleta(String $format = 'd/m/Y H:i')
    {
        if (! $this->data_coleta) {

            return $this->data_coleta;

        }

        return Carbon::parse($this->data_coleta)->format($format);
    }
}
