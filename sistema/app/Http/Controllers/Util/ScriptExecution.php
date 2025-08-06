<?php

namespace App\Http\Controllers\Util;

use App\Models\AnalisesLaboratoriais;
use App\Models\AnalisesLaboratoriaisNew;
use App\Models\AnalisesBacteriologicas;
use App\Models\AnalisesBacteriologicasNew;
use App\Models\AnalisesCalcicas;
use App\Models\AnalisesCalcicasNew;

class ScriptExecution 
{
    public static function execute()
    {
        // return static::migrateTableAnalisesLaboratoriais();
    }

    private static function migrateTableAnalisesLaboratoriais()
    {
        $models = [
            AnalisesBacteriologicas::class,
            AnalisesCalcicas::class,
        ];

        foreach ($models as $model) {

            $coletas = $model::all();

            foreach ($coletas as $coleta) {

                if (! isset($coleta->analise_laboratorial)) {
                    continue;
                }

                $data = [
                    'filial_id'                    => $coleta->analise_laboratorial->filial_id,
                    'data_analise'                 => $coleta->analise_laboratorial->data_analise,
                    'analise_laboratorial_tipo_id' => $coleta->analise_laboratorial->analise_laboratorial_tipo_id,
                    'tanque_tipo_id'               => $coleta->tanque->tanque_tipo_id,
                ];

                $analise = AnalisesLaboratoriaisNew::where(function ($query) use ($data) {
                    $query->where('filial_id',                    $data['filial_id']);
                    $query->where('data_analise',                 $data['data_analise']);
                    $query->where('analise_laboratorial_tipo_id', $data['analise_laboratorial_tipo_id']);
                    $query->where('tanque_tipo_id',               $data['tanque_tipo_id']);
                })->orderBy('id', 'desc')
                ->get();

                if ($analise->isEmpty()) {
                    $analise = AnalisesLaboratoriaisNew::create($data);
                } else {
                    $analise = $analise->first();
                }

                $data = $coleta->toArray();
    
                $data['analise_laboratorial_id'] = $analise->id;
    
                unset(
                    $data['id'],
                    $data['criado_em'],
                    $data['alterado_em'],
                    $data['analise_laboratorial'],
                    $data['tanque']
                );
    
                $modelNew = "{$model}New";

                $coleta = $modelNew::where(function ($query) use ($data) {
                    $query->where('analise_laboratorial_id', $data['analise_laboratorial_id']);
                    $query->where('tanque_id', $data['tanque_id']);
                })->orderBy('id', 'desc')
                ->get();

                if ($coleta->isEmpty()) {

                    $coleta = $modelNew::create($data);

                    if (! $coleta instanceof $modelNew) {
                        return false;
                    }

                }

            }

        }

        return true;
    }
}