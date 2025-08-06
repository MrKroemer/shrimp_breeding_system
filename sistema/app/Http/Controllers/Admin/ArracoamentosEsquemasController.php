<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArracoamentosEsquemas;
use App\Models\ArracoamentosPerfis;
use App\Http\Requests\ArracoamentosEsquemasCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class ArracoamentosEsquemasController extends Controller
{
    private $rowsPerPage = 10;
    private $periodos = [
        'N'  => 'Normal',
        'T'  => 'Transição',
    ];

    public function listingArracoamentosEsquemas(int $arracoamento_perfil_id)
    {
        $arracoamentos_esquemas = ArracoamentosEsquemas::listing($this->rowsPerPage, [
            'arracoamento_perfil_id' => $arracoamento_perfil_id
        ]);

        $arracoamento_perfil = ArracoamentosPerfis::find($arracoamento_perfil_id);

        return view('admin.arracoamentos_esquemas.list')
        ->with('arracoamentos_esquemas', $arracoamentos_esquemas)
        ->with('arracoamento_perfil_id', $arracoamento_perfil_id)
        ->with('arracoamento_perfil',    $arracoamento_perfil);
    }

    public function searchArracoamentosEsquemas(Request $request, int $arracoamento_perfil_id)
    {
        $formData = $request->except(['_token']);
        $formData['arracoamento_perfil_id'] = $arracoamento_perfil_id;

        $arracoamentos_esquemas = ArracoamentosEsquemas::search($formData, $this->rowsPerPage);

        $arracoamento_perfil = ArracoamentosPerfis::find($arracoamento_perfil_id);

        return view('admin.arracoamentos_esquemas.list')
        ->with('formData',               $formData)
        ->with('arracoamentos_esquemas', $arracoamentos_esquemas)
        ->with('arracoamento_perfil_id', $arracoamento_perfil_id)
        ->with('arracoamento_perfil',    $arracoamento_perfil);
    }

    public function createArracoamentosEsquemas(int $arracoamento_perfil_id)
    {
        $arracoamento_perfil = ArracoamentosPerfis::find($arracoamento_perfil_id);

        return view('admin.arracoamentos_esquemas.create')
        ->with('arracoamento_perfil_id', $arracoamento_perfil_id)
        ->with('arracoamento_perfil',    $arracoamento_perfil)
        ->with('periodos', $this->periodos);
    }

    public function storeArracoamentosEsquemas(ArracoamentosEsquemasCreateFormRequest $request, int $arracoamento_perfil_id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['arracoamento_perfil_id'] = $arracoamento_perfil_id;

        if ($data['dia_inicial'] > $data['dia_final']) {
            return redirect()->back()
            ->with('dia_inicial',            $data['dia_inicial'])
            ->with('dia_final',              $data['dia_final'])
            ->with('descricao',              $data['descricao'])
            ->with('periodo',                $data['periodo'])
            ->with('arracoamento_perfil_id', $arracoamento_perfil_id)
            ->with('warning', 'O primeiro dia do intervalo, não pode estar a frente do último dia informado.');
        }

        $insert = ArracoamentosEsquemas::create($data);

        if ($insert) {
            return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas', ['arracoamento_perfil_id' => $arracoamento_perfil_id])
            ->with('success', 'Esquema de alimentação cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('dia_inicial',            $data['dia_inicial'])
        ->with('dia_final',              $data['dia_final'])
        ->with('descricao',              $data['descricao'])
        ->with('periodo',                $data['periodo'])
        ->with('arracoamento_perfil_id', $arracoamento_perfil_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o esquema de alimentação. Tente novamente!');
    }

    public function editArracoamentosEsquemas(int $arracoamento_perfil_id, int $id)
    {
        $data = ArracoamentosEsquemas::find($id);

        $arracoamento_perfil = ArracoamentosPerfis::find($arracoamento_perfil_id);

        return view('admin.arracoamentos_esquemas.edit')
        ->with('id',                     $id)
        ->with('dia_inicial',            $data['dia_inicial'])
        ->with('dia_final',              $data['dia_final'])
        ->with('descricao',              $data['descricao'])
        ->with('periodo',                $data['periodo'])
        ->with('arracoamento_perfil_id', $arracoamento_perfil_id)
        ->with('arracoamento_perfil',    $arracoamento_perfil)
        ->with('periodos',               $this->periodos);
    }

    public function updateArracoamentosEsquemas(ArracoamentosEsquemasCreateFormRequest $request, int $arracoamento_perfil_id, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);
        
        $data['arracoamento_perfil_id'] = $arracoamento_perfil_id;

        if ($data['dia_inicial'] > $data['dia_final']) {
            return redirect()->back()
            ->with('id',                     $id)
            ->with('arracoamento_perfil_id', $arracoamento_perfil_id)
            ->with('warning', 'O primeiro dia do intervalo, não pode estar a frente do último dia informado.');
        }

        $update = ArracoamentosEsquemas::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas', ['arracoamento_perfil_id' => $arracoamento_perfil_id])
            ->with('success', 'Esquema de alimentação atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id',                     $id)
        ->with('arracoamento_perfil_id', $arracoamento_perfil_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o esquema de alimentação. Tente novamente!');
    }

    public function removeArracoamentosEsquemas(int $arracoamento_perfil_id, int $id)
    {
        $data = ArracoamentosEsquemas::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas', ['arracoamento_perfil_id' => $arracoamento_perfil_id])
            ->with('success', 'Esquema de alimentação excluido com sucesso!');
        } 
        
        return redirect()->route('admin.arracoamentos_perfis.arracoamentos_esquemas', ['arracoamento_perfil_id' => $arracoamento_perfil_id])
        ->with('error', 'Ocorreu um erro ao excluir o esquema de alimentação. Tente novamente!');
    }
}
