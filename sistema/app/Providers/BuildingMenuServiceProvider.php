<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\Http\Middleware\CheckAccessPermission;
use App\Models\Menus;

class BuildingMenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) 
        {    
            if (empty(session('_menu_options'))) {
                session(['_menu_options' => Menus::orderBy('nome')->get()]);
            }

            $menuItems         = session('_menu_options');
            $moduloSelecionado = session('_selected_module');
            $permissoesUsuario = CheckAccessPermission::getUserPermissions(auth()->user());

            foreach ($menuItems as $menuOption) {

                if ($menuOption->tipo == 'menu' &&  $menuOption->situacao == 'ON') {

                    $submenuItems = [];

                    foreach ($menuItems as $submenuOption) {

                        if ($submenuOption->tipo == 'submenu' && $submenuOption->situacao == 'ON' && $submenuOption->menu_id  == $menuOption->id) {

                            foreach ($permissoesUsuario as $permissao) {

                                if ($permissao->menu_id   == $submenuOption->id && $permissao->modulo_id == $moduloSelecionado) {

                                    array_push($submenuItems, [
                                        'text'  => $submenuOption->nome,
                                        'icon'  => $submenuOption->icone,
                                        'route' => $submenuOption->rota,
                                    ]);

                                }

                            }

                        }

                    }

                    if (count($submenuItems) != 0) {

                        $event->menu->add([
                            'text'    => $menuOption->nome,
                            'icon'    => $menuOption->icone,
                            'submenu' => $submenuItems,
                        ]);

                    }

                }

            }
        });
    }
}
