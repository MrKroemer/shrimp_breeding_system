<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwMovimentos extends Model
{
    protected $table = 'vw_movimentos';

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }
    
    public static function search(Array $data, int $rowsPerPage = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['data_movimento'])) {
                $query->where('data_movimento', $data['data_movimento']);
            }

            if (isset($data['movimento'])) {
                $query->where('movimento', $data['movimento']);
            }

            if (isset($data['situacao'])) {
                $query->where('situacao', $data['situacao']);
            }

            if (isset($data['tanque'])) {
                $query->where('tanque', $data['tanque']);
            }

        })
        ->orderBy('data_movimento', 'desc')
        ->orderBy('situacao',             )
        ->orderBy('tanque',                )
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('situacao', '<>', 'V');
        })
        ->orderBy('data_movimento', 'desc')
        ->orderBy('situacao'              )
        ->paginate($rowsPerPage);
    }
}
