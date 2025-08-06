<?php

namespace App\Http\Controllers\Util;

use Illuminate\Http\Request;

class RouteManipulator
{
    public static function redirectTo(Request $request, int $module, String $route, Array $parameters = [], Array $with = [])
    {
        $session = $request->session();

        $message['type'] = '';
        $message['text'] = '';

        if ($session->has('success')) {

            $message['type'] = 'success';
            $message['text'] = $session->get('success');

        } elseif ($session->has('warning')) {

            $message['type'] = 'warning';
            $message['text'] = $session->get('warning');

        } elseif ($session->has('error')) {

            $message['type'] = 'error';
            $message['text'] = $session->get('error');

        }

        $redirectTo = redirect()->route($route, $parameters)
        ->with($message['type'], $message['text']);

        if (! empty($with)) {

            foreach ($with as $name => $value) {

                $redirectTo->with($name, $value);

            }

        }
        
        session(['_selected_module' => $module]);

        return $redirectTo;
    }
}
