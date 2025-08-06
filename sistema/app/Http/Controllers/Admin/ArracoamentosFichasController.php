<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Arracoamentos;
use App\Models\Setores;
use App\Http\Controllers\Reports\ArracoamentosFichasReport;
use Validator;

class ArracoamentosFichasController extends Controller
{
    public function generateArracoamentosFichas(Request $request)
    {
        $data = $request->except(['_token']);

        if (empty($data)) {
            return redirect()->back()
            ->with('warning', 'Pelo menos um arraçoamento deve ser selecionado.');
        }

        $arracoamentos = [];

        foreach ($data as $key => $value) {
            
            if (strpos($key, 'arracoamento_') === 0) {

                $arracoamento_id = str_replace('arracoamento_', '', $key);

                $arracoamento = Arracoamentos::find($arracoamento_id);

                if ($arracoamento instanceof Arracoamentos) {
                    $arracoamentos[] = $arracoamento;
                }

            }

        }

        $document = new ArracoamentosFichasReport();
        
        $document->MakeDocument(['arracoamentos' => $arracoamentos]);
        
        $fileName = 'ficha_de_arracoamento_' . date('Y-m-d_H-i-s');

        return $document->Output($fileName, 'I');
    }
}
