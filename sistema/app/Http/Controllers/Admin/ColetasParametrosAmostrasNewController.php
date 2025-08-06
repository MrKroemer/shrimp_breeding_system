<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ColetasParametrosNew;
use App\Models\ColetasParametrosAmostrasNew;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\SondasFatores;

class ColetasParametrosAmostrasNewController extends Controller
{
    private $rowsPerPage = 10;

    public function create(int $coleta_parametro_id)
    {
        $coleta_parametro = ColetasParametrosNew::find($coleta_parametro_id);

        $coleta_parametro_ant = ColetasParametrosNew::where('tanque_tipo_id', $coleta_parametro->tanque_tipo_id)
        ->where('coleta_parametro_tipo_id', $coleta_parametro->coleta_parametro_tipo_id)
        ->where('data_coleta', '<', $coleta_parametro->data_coleta)
        ->orderBy('data_coleta', 'desc')
        ->first();

        $tanques = $coleta_parametro->tanque_tipo->tanques
        ->where('filial_id', session('_filial')->id)
        ->where('situacao', 'ON')
        ->sortBy('sigla');

        return view('admin.coletas_parametros_new.amostras.create')
        ->with('coleta_parametro',     $coleta_parametro)
        ->with('coleta_parametro_ant', $coleta_parametro_ant)
        ->with('tanques',              $tanques);
    }

    public function store(Request $request, int $coleta_parametro_id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_NULL']);

        $coleta_parametro = ColetasParametrosNew::find($coleta_parametro_id);
        
        //dd($data);

        //Verifica se existe um fator de conversão

        $data['fator'] = 1;

        $sonda_fator = SondasFatores::where('sonda_laboratorial_id',$coleta_parametro->sonda_laboratorial_id)
        ->where('coleta_parametro_tipo_id',$coleta_parametro->coleta_parametro_tipo_id)
        ->where('situacao','ON')
        ->first();

        if((! $sonda_fator instanceof SondasFatores)){
            $data['fator'] = 0;
        }

        $tanques = $coleta_parametro->tanque_tipo->tanques
        ->where('filial_id', session('_filial')->id)
        ->where('situacao', 'ON');

        $amostras = $coleta_parametro->amostras;

        $usuario_id = auth()->user()->id;

        $registros = [];

        foreach ($data as $key => $value) {

            if (strpos($key, 'medicao') === 0 && ! is_null($value)) {

                $dados = str_replace('medicao', '', $key);
                $dados = explode('_', $dados);

                $valor_fatorado = ($data['fator'] == 1) ? ($value * $sonda_fator->fator) : null;
                $medicao        = $dados[0];
                $tanque_id      = $dados[1];
                $amostra_id     = 0;

                if (isset($dados[2]) && is_numeric($dados[2]) && $dados[2] > 0) {
                    $amostra_id = $dados[2];
                }

                if ($tanques->where('id', $tanque_id)->isNotEmpty() && is_numeric($medicao)) {
                    
                    if ($amostras->where('id', $amostra_id)->isNotEmpty()) {
                        
                        //verifica se existe o fator de conversão, se existir faz o calculo
                        
                        $amostra = ColetasParametrosAmostrasNew::find($amostra_id)->update([
                            'valor'               => $value,
                            'valor_fatorado'      => $valor_fatorado,
                            'usuario_id'          => $usuario_id,
                        ]);

                        $registros[] = $amostra ? 1 : 0;

                    } elseif ($amostras->where('tanque_id', $tanque_id)->where('medicao', $medicao)->isEmpty()) {
                        
                        $amostra = ColetasParametrosAmostrasNew::create([
                            'coleta_parametro_id' => $coleta_parametro_id,
                            'valor'               => $value,
                            'valor_fatorado'      => $valor_fatorado,
                            'medicao'             => $medicao,
                            'tanque_id'           => $tanque_id,
                            'usuario_id'          => $usuario_id,
                        ]);

                        $registros[] = ($amostra instanceof ColetasParametrosAmostrasNew) ? 1 : 0;

                    }

                }

            }

        }

        $erros = count($registros) - array_sum($registros);
        
        if ($erros == 0) {
            return redirect()->route('admin.coletas_parametros_new.amostras.to_create', [
                'coleta_parametro_id' => $coleta_parametro_id
            ])
            ->with('success', 'Registros salvos com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', "Ocorreu um erro durante a tentativa de salvar {$erros} dos registros! Tente novamente.");
    }

    public function remove(int $coleta_parametro_id, int $id)
    {
        $amostra = ColetasParametrosAmostrasNew::find($id);

        if ($amostra->delete()) {
            return redirect()->back()
            ->with('success', 'Registros excluidos com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir os registros! Tente novamente.');
    }
}
