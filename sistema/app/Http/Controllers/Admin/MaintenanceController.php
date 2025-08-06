<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\ScriptExecution;

class MaintenanceController extends Controller
{
    public function execute()
    {
        if (ScriptExecution::execute()) {
            return redirect()->back()
            ->with('success', 'Executado com sucesso.');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a execução.');
    }

    public function buildingPage()
    {
        return view('maintenance.building');
    }

    public function error403Page()
    {
        $redirectBack = redirect()->back()->getTargetUrl();

        return view('maintenance.error403')
        ->with('redirectBack', $redirectBack);
    }

    public function testsPage()
    {
        return view('maintenance.tests');
    }

    public function versionsPage()
    {
        return view('maintenance.versions');
    }
}
