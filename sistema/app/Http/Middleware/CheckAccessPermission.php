<?php

namespace App\Http\Middleware;

use Closure;

class CheckAccessPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $rotaRequisitada   = explode('.', $request->route()->getName());
        $numeroRotas       = count($rotaRequisitada) - 1;
        $moduloSelecionado = session('_selected_module');
        $permissoesUsuario = static::getUserPermissions(auth()->user());

        foreach ($permissoesUsuario as $permissaoUsuario) {

            $permissoes = explode('|', $permissaoUsuario->permissoes);
                                
            $create = false;
            $read   = false;
            $update = false;
            $delete = false;

            foreach ($permissoes as $permissao) {
                switch ($permissao) {
                    case 'C': $create = true; break;
                    case 'R': $read   = true; break;
                    case 'U': $update = true; break;
                    case 'D': $delete = true; break;
                }
            }

            if ($moduloSelecionado == $permissaoUsuario->modulo_id) {

                for ($i = $numeroRotas; $i >= 0; $i --) {

                    if ('admin.'.$rotaRequisitada[$i] == $permissaoUsuario->menu->rota) {
                        
                        switch ($rotaRequisitada[$numeroRotas]) {

                            case 'to_create': 
                            case 'to_store': 
                            case 'to_generate': 
                            case 'to_import': 
                                if ($create) { return $next($request); }
                                break;

                            case 'to_edit': 
                            case 'to_update': 
                            case 'to_turn': 
                            case 'to_reverse': 
                            case 'to_close': 
                                if ($update) { return $next($request); }
                                break;

                            case 'to_remove': 
                                if ($delete) { return $next($request); }
                                break;

                            case 'to_view': 
                            default: 
                                if ($read) { return $next($request); }
                                break;

                        }

                    }

                }

            }

        }

        return redirect()->route('admin.error403');
    }

    /**
     * retorna as permissoes de acesso do usuario
     *
     * @param  \App\Models\Usuarios  $usuario
     * @return array
     */
    public static function getUserPermissions($usuario)
    {
        $permissoesUsuario = [];

        if ($usuario->situacao == 'ON') {
        
            if ($usuario->grupos->isNotEmpty()) {

                foreach ($usuario->grupos as $usuario_grupo) {

                    if ($usuario_grupo->grupo->situacao == 'ON') {

                        foreach ($usuario_grupo->grupo->filiais->where('filial_id', session('_filial')->id) as $filial) {
    
                            if ($filial->situacao == 'ON') {
        
                                foreach ($filial->permissoes as $permissao) {
        
                                    $permissoesUsuario[$permissao->modulo_id . $permissao->menu_id] = (object) [
                                        'modulo_id'  => $permissao->modulo_id,
                                        'menu_id'    => $permissao->menu_id,
                                        'permissoes' => $permissao->permissoes,
                                        'menu'       => $permissao->menu,
                                    ];
        
                                }
        
                            }
        
                        }

                    }
    
                }

            }

            foreach ($usuario->filiais->where('filial_id', session('_filial')->id) as $filial) {

                if ($filial->situacao == 'ON') {

                    foreach ($filial->permissoes as $permissao) {

                        $permissoesUsuario[$permissao->modulo_id . $permissao->menu_id] = (object) [
                            'modulo_id'  => $permissao->modulo_id,
                            'menu_id'    => $permissao->menu_id,
                            'permissoes' => $permissao->permissoes,
                            'menu'       => $permissao->menu,
                        ];

                    }

                }

            }

        }

        return $permissoesUsuario;
    }
}
