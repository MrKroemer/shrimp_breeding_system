<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GruposRateios;
use App\Models\GruposRateiosTanques;
use App\Models\VwGruposRateiosTanques;
use App\Models\Tanques;
use Validator;

class GruposRateiosTanquesController extends Controller
{
    public function listingGruposRateiosTanques(int $grupo_rateio_id)
    {
        $grupo_rateio = GruposRateios::find($grupo_rateio_id);

        if (! $grupo_rateio instanceof GruposRateios) {
            return redirect()->route('admin.grupos_rateios')
            ->with('warning', 'O grupo de rateio informado, não existe.');
        }

        $grupo_tanques = VwGruposRateiosTanques::where('grupo_rateio_id', $grupo_rateio_id)
        ->orderBy('tanque_sigla');

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->whereNotIn('id', $grupo_tanques->get(['tanque_id']))
        ->orderBy('sigla')
        ->get();

        $grupo_tanques = $grupo_tanques->get();

        $enviam = $grupo_tanques->where('tipo', 'E');
        $recebem = $grupo_tanques->where('tipo', 'R');

        return view('admin.grupos_rateios.tanques.list')
        ->with('grupo_rateio_id', $grupo_rateio_id)
        ->with('tanques',         $tanques)
        ->with('enviam',          $enviam)
        ->with('recebem',         $recebem)
        ->with('grupo_rateio',    $grupo_rateio);
    }

    public function storeGruposRateiosTanques(Request $request, int $grupo_rateio_id)
    {
        $data = $request->except('_token');

        $rules = [
            'tanques' => 'required',
            'tipo'    => 'required',
        ];

        $messages = [
            'tanques.required' => 'Ao menos um tanque deve ser informado.',
            'tipo.required'    => 'A finalidade deve ser informada.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        $redirect = redirect()->route('admin.grupos_rateios.grupos_tanques', ['grupo_rateio_id' => $grupo_rateio_id]);

        if ($validator->fails()) {
            return $redirect->withErrors($validator)->withInput();
        }

        $data['grupo_rateio_id'] = $grupo_rateio_id;
        $data['usuario_id'] = auth()->user()->id;

        foreach ($data['tanques'] as $tanque_id) {

            $tanque = Tanques::find($tanque_id);

            if ($tanque instanceof Tanques) {

                $grupo_rateio = GruposRateiosTanques::where('tanque_id', $tanque->id)
                ->where('grupo_rateio_id', $grupo_rateio_id)
                ->get();

                if ($grupo_rateio->isNotEmpty()) {
                    continue;
                }
                
                $data['tanque_id'] = $tanque->id;

                $grupo_tanque = GruposRateiosTanques::create($data);

                if ($grupo_tanque instanceof GruposRateiosTanques) {
                    continue;
                }

            }

            return $redirect->with('warning', 'Alguns tanques não foram registrados.');

        }

        return $redirect;

    }

    public function removeGruposRateiosTanques(int $grupo_rateio_id, int $id)
    {
        $remove = GruposRateiosTanques::find($id);

        if ($remove->delete()) {
            return redirect()->back();
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de exclusão do registro! Tente novamente.');
    }

    public function turnGruposRateiosTanques(int $grupo_rateio_id, int $id)
    {
        $data = GruposRateiosTanques::find($id);

        if ($data->tipo == 'E') {

            $data->tipo = 'R';
            $data->save();

            return redirect()->back();

        }

        $data->tipo = 'E';
        $data->save();

        return redirect()->back();
    }
}
