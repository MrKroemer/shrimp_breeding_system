<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Charts\RouteTestGraphicCharts;
use Mpdf\Tag\NewColumn;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
//Route::get('graficos/amostras/test', 'RouteTestGraphicCharts@routeTest')->name('routeTest');
;
/*
|--------------------------------------------------------------------------
| Rotas que necessitam de autenticação de usuário para acesso
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Rotas que não necessitam de permissões de acesso
    |--------------------------------------------------------------------------
    */
    Route::namespace('Admin')->group(function() {

        Route::get('/', 'DashboardController@dashboard')->name('admin');

        // Rotas dos itens do dashboard
        Route::get('/dashboard', 'DashboardController@dashboard')->name('admin.dashboard');
        Route::get('/dashboard/setores/{setor_id}/tanques', 'DashboardController@tanques')->name('admin.dashboard.tanques');
        Route::get('/dashboard/setores/{setor_id}/tanques/{tanque_id}/informacoes', 'DashboardController@tanquesInformacoes')->name('admin.dashboard.informacoes');
        Route::get('/dashboard/setores/{setor_id}/tanques/{tanque_id}/lote_peixes/{lote_peixes_id}/informacoes', 'DashboardController@tanquesInformacoesPeixes')->name('admin.dashboard.informacoes_peixes');
    
        // Rotas para as paginas de erros, manutenção e testes
        Route::get('/tests', 'MaintenanceController@testsPage')->name('admin.tests');
        Route::get('/execute', 'MaintenanceController@execute')->name('admin.execute');
        Route::get('/building', 'MaintenanceController@buildingPage')->name('admin.building');
        Route::get('/error403', 'MaintenanceController@error403Page')->name('admin.error403');
        Route::get('/versions', 'MaintenanceController@versionsPage')->name('admin.versions');
    
        // Rotas de APIs da aplicação
        Route::get('/api/collection/ciclos/numero/{tipo}', 'CiclosController@getNumeroCiclo')->name('api.collection.tanques.numero');
        Route::get('/api/collection/tanques/{tanque_tipo_id}', 'TanquesController@getJsonTanques')->name('api.collection.tanques');
        Route::get('/api/collection/subsetores/{setor_id}', 'SubsetoresController@getJsonSubsetores')->name('api.collection.subsetores');
        Route::get('/api/collection/estados/{pais_id}', 'EstadosController@getJsonEstados')->name('api.collection.estados');
        Route::get('/api/collection/cidades/{estado_id}', 'CidadesController@getJsonCidades')->name('api.collection.cidades');
        Route::get('/api/collection/menus/opcoes/{modulo_id}', 'MenusController@getJsonOpcoes')->name('api.collection.menus.opcoes');
        Route::get('/api/collection/menus/itens/{menu_id}', 'MenusController@getJsonItens')->name('api.collection.menus.itens');
        Route::get('/api/collection/ciclos/situacao/{ciclo_situacao}/setor/{setor_id}', 'CiclosController@getJsonVwCiclosPorSetor')->name('api.collection.ciclos_por_setor');
        Route::get('/api/collection/arracoamentos_aplicacoes/itens/{arracoamento_aplicacao_tipo_id}', 'ArracoamentosAplicacoesController@getJsonItens')->name('api.collection.arracoamentos_aplicacoes.itens');
        Route::get('/api/collection/transferencias_animais/{ciclo_id}', 'TransferenciasAnimaisController@getJsonCiclosDestino')->name('api.collection.ciclos.transferencias_animais');
    
        // Rota do controlador de unidades de medidas
        Route::post('/unidades_medidas/store', 'UnidadesMedidasController@storeUnidadesMedidas')->name('admin.unidades_medidas.to_store');

        // Rota do controlador de espécies
        Route::post('/especies/store', 'EspeciesController@storeEspecies')->name('admin.especies.to_store');

        // Rota para alteração da imagem do perfil de usuário
        Route::post('/usuarios/{id}/imagem/update', 'UsuariosController@updateUsuariosImagem')->name('admin.usuarios.imagem.to_update');

    });

    /*
    |--------------------------------------------------------------------------
    | Rotas que necessitam de permissões de acesso
    |--------------------------------------------------------------------------
    */
    Route::middleware('permission.check')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Rotas de funcionalidades e CRUD's
        |--------------------------------------------------------------------------
        */
        Route::namespace('Admin')->group(function() {

            // Rotas do controlador de usuários
            Route::get('/usuarios', 'UsuariosController@listingUsuarios')->name('admin.usuarios');
            Route::any('/usuarios/search', 'UsuariosController@searchUsuarios')->name('admin.usuarios.to_search');
            Route::get('/usuarios/create', 'UsuariosController@createUsuarios')->name('admin.usuarios.to_create');
            Route::post('/usuarios/store', 'UsuariosController@storeUsuarios')->name('admin.usuarios.to_store');
            Route::get('/usuarios/{id}/edit', 'UsuariosController@editUsuarios')->name('admin.usuarios.to_edit');
            Route::post('/usuarios/{id}/update', 'UsuariosController@updateUsuarios')->name('admin.usuarios.to_update');
            Route::get('/usuarios/{id}/remove', 'UsuariosController@removeUsuarios')->name('admin.usuarios.to_remove');
            Route::get('/usuarios/{id}/turn', 'UsuariosController@turnUsuarios')->name('admin.usuarios.to_turn');
    
            // Rotas do controlador de cadastro de filiais para usuários
            Route::get('/usuarios/{usuario_id}/filiais', 'UsuariosFiliaisController@listingUsuariosFiliais')->name('admin.usuarios.filiais');
            Route::any('/usuarios/{usuario_id}/filiais/search', 'UsuariosFiliaisController@searchUsuariosFiliais')->name('admin.usuarios.filiais.to_search');
            Route::post('/usuarios/{usuario_id}/filiais/store', 'UsuariosFiliaisController@storeUsuariosFiliais')->name('admin.usuarios.filiais.to_store');
            Route::post('/usuarios/{usuario_id}/filiais/{id}/update', 'UsuariosFiliaisController@updateUsuariosFiliais')->name('admin.usuarios.filiais.to_update');
            Route::get('/usuarios/{usuario_id}/filiais/{id}/remove', 'UsuariosFiliaisController@removeUsuariosFiliais')->name('admin.usuarios.filiais.to_remove');
            Route::get('/usuarios/{usuario_id}/filiais/{id}/turn', 'UsuariosFiliaisController@turnUsuariosFiliais')->name('admin.usuarios.filiais.to_turn');
    
            // Rotas do controlador de permissoes de usuário
            Route::get('/usuarios/{usuario_id}/filiais/{usuario_filial_id}/permissoes', 'UsuariosPermissoesController@listingUsuariosPermissoes')->name('admin.usuarios.filiais.permissoes');
            Route::any('/usuarios/{usuario_id}/filiais/{usuario_filial_id}/permissoes/search', 'UsuariosPermissoesController@searchUsuariosPermissoes')->name('admin.usuarios.filiais.permissoes.to_search');
            Route::post('/usuarios/{usuario_id}/filiais/{usuario_filial_id}/permissoes/store', 'UsuariosPermissoesController@storeUsuariosPermissoes')->name('admin.usuarios.filiais.permissoes.to_store');
            Route::post('/usuarios/{usuario_id}/filiais/{usuario_filial_id}/permissoes/{id}/update', 'UsuariosPermissoesController@updateUsuariosPermissoes')->name('admin.usuarios.filiais.permissoes.to_update');
            Route::get('/usuarios/{usuario_id}/filiais/{usuario_filial_id}/permissoes/{id}/remove', 'UsuariosPermissoesController@removeUsuariosPermissoes')->name('admin.usuarios.filiais.permissoes.to_remove');
    
            // Rotas do controlador de usuários dos grupos
            Route::get('/usuarios/{usuario_id}/usuarios_grupos', 'UsuariosGruposController@listingUsuariosGrupos')->name('admin.usuarios.usuarios_grupos');
            Route::any('/usuarios/{usuario_id}/usuarios_grupos/search', 'UsuariosGruposController@searchUsuariosGrupos')->name('admin.usuarios.usuarios_grupos.to_search');
            Route::post('/usuarios/{usuario_id}/usuarios_grupos/store', 'UsuariosGruposController@storeUsuariosGrupos')->name('admin.usuarios.usuarios_grupos.to_store');
            Route::post('/usuarios/{usuario_id}/usuarios_grupos/{id}/update', 'UsuariosGruposController@updateUsuariosGrupos')->name('admin.usuarios.usuarios_grupos.to_update');
            Route::get('/usuarios/{usuario_id}/usuarios_grupos/{id}/remove', 'UsuariosGruposController@removeUsuariosGrupos')->name('admin.usuarios.usuarios_grupos.to_remove');

            // Rotas do controlador de grupos
            Route::get('/grupos', 'GruposController@listingGrupos')->name('admin.grupos');
            Route::any('/grupos/search', 'GruposController@searchGrupos')->name('admin.grupos.to_search');
            Route::get('/grupos/create', 'GruposController@createGrupos')->name('admin.grupos.to_create');
            Route::post('/grupos/store', 'GruposController@storeGrupos')->name('admin.grupos.to_store');
            Route::get('/grupos/{id}/edit', 'GruposController@editGrupos')->name('admin.grupos.to_edit');
            Route::post('/grupos/{id}/update', 'GruposController@updateGrupos')->name('admin.grupos.to_update');
            Route::get('/grupos/{id}/remove', 'GruposController@removeGrupos')->name('admin.grupos.to_remove');
            Route::get('/grupos/{id}/turn', 'GruposController@turnGrupos')->name('admin.grupos.to_turn');
            
            // Rotas do controlador de cadastro de filiais para grupos
            Route::get('/grupos/{grupo_id}/filiais', 'GruposFiliaisController@listingGruposFiliais')->name('admin.grupos.filiais');
            Route::any('/grupos/{grupo_id}/filiais/search', 'GruposFiliaisController@searchGruposFiliais')->name('admin.grupos.filiais.to_search');
            Route::post('/grupos/{grupo_id}/filiais/store', 'GruposFiliaisController@storeGruposFiliais')->name('admin.grupos.filiais.to_store');
            Route::post('/grupos/{grupo_id}/filiais/{id}/update', 'GruposFiliaisController@updateGruposFiliais')->name('admin.grupos.filiais.to_update');
            Route::get('/grupos/{grupo_id}/filiais/{id}/remove', 'GruposFiliaisController@removeGruposFiliais')->name('admin.grupos.filiais.to_remove');
            Route::get('/grupos/{grupo_id}/filiais/{id}/turn', 'GruposFiliaisController@turnGruposFiliais')->name('admin.grupos.filiais.to_turn');
    
            // Rotas do controlador de permissoes de grupos
            Route::get('/grupos/{grupo_id}/filiais/{grupo_filial_id}/permissoes', 'GruposPermissoesController@listingGruposPermissoes')->name('admin.grupos.filiais.permissoes');
            Route::any('/grupos/{grupo_id}/filiais/{grupo_filial_id}/permissoes/search', 'GruposPermissoesController@searchGruposPermissoes')->name('admin.grupos.filiais.permissoes.to_search');
            Route::post('/grupos/{grupo_id}/filiais/{grupo_filial_id}/permissoes/store', 'GruposPermissoesController@storeGruposPermissoes')->name('admin.grupos.filiais.permissoes.to_store');
            Route::post('/grupos/{grupo_id}/filiais/{grupo_filial_id}/permissoes/{id}/update', 'GruposPermissoesController@updateGruposPermissoes')->name('admin.grupos.filiais.permissoes.to_update');
            Route::get('/grupos/{grupo_id}/filiais/{grupo_filial_id}/permissoes/{id}/remove', 'GruposPermissoesController@removeGruposPermissoes')->name('admin.grupos.filiais.permissoes.to_remove');
    
            // Rotas do controlador de grupos de usuários
            Route::get('/grupos/{grupo_id}/grupos_usuarios', 'GruposUsuariosController@listingGruposUsuarios')->name('admin.grupos.grupos_usuarios');
            Route::any('/grupos/{grupo_id}/grupos_usuarios/search', 'GruposUsuariosController@searchGruposUsuarios')->name('admin.grupos.grupos_usuarios.to_search');
            Route::post('/grupos/{grupo_id}/grupos_usuarios/store', 'GruposUsuariosController@storeGruposUsuarios')->name('admin.grupos.grupos_usuarios.to_store');
            Route::post('/grupos/{grupo_id}/grupos_usuarios/{id}/update', 'GruposUsuariosController@updateGruposUsuarios')->name('admin.grupos.grupos_usuarios.to_update');
            Route::get('/grupos/{grupo_id}/grupos_usuarios/{id}/remove', 'GruposUsuariosController@removeGruposUsuarios')->name('admin.grupos.grupos_usuarios.to_remove');
    
            // Rotas do controlador de filiais
            Route::get('/filiais', 'FiliaisController@listingFiliais')->name('admin.filiais');
            Route::any('/filiais/search', 'FiliaisController@searchFiliais')->name('admin.filiais.to_search');
            Route::get('/filiais/create', 'FiliaisController@createFiliais')->name('admin.filiais.to_create');
            Route::post('/filiais/store', 'FiliaisController@storeFiliais')->name('admin.filiais.to_store');
            Route::get('/filiais/{id}/edit', 'FiliaisController@editFiliais')->name('admin.filiais.to_edit');
            Route::post('/filiais/{id}/update', 'FiliaisController@updateFiliais')->name('admin.filiais.to_update');
            Route::get('/filiais/{id}/remove', 'FiliaisController@removeFiliais')->name('admin.filiais.to_remove');
    
            // Rotas do registro de setores
            Route::get('/setores', 'SetoresController@listing')->name('admin.setores');
            Route::any('/setores/search', 'SetoresController@search')->name('admin.setores.to_search');
            Route::get('/setores/create', 'SetoresController@create')->name('admin.setores.to_create');
            Route::post('/setores/store', 'SetoresController@store')->name('admin.setores.to_store');
            Route::get('/setores/{id}/edit', 'SetoresController@edit')->name('admin.setores.to_edit');
            Route::post('/setores/{id}/update', 'SetoresController@update')->name('admin.setores.to_update');
            Route::get('/setores/{id}/remove', 'SetoresController@remove')->name('admin.setores.to_remove');
    
            // Rotas do controlador de subsetores
            Route::get('subsetores', 'SubsetoresController@listingSubsetores')->name('admin.subsetores');
            Route::any('subsetores/search', 'SubsetoresController@searchSubsetores')->name('admin.subsetores.to_search');
            Route::get('subsetores/create', 'SubsetoresController@createSubsetores')->name('admin.subsetores.to_create');
            Route::post('subsetores/store', 'SubsetoresController@storeSubsetores')->name('admin.subsetores.to_store');
            Route::get('subsetores/{id}/edit', 'SubsetoresController@editSubsetores')->name('admin.subsetores.to_edit');
            Route::post('subsetores/{id}/update', 'SubsetoresController@updateSubsetores')->name('admin.subsetores.to_update');
            Route::get('subsetores/{id}/remove', 'SubsetoresController@removeSubsetores')->name('admin.subsetores.to_remove');
    
            // Rotas do controlador de tanques
            Route::get('/tanques', 'TanquesController@listingTanques')->name('admin.tanques');
            Route::any('/tanques/search', 'TanquesController@searchTanques')->name('admin.tanques.to_search');
            Route::get('/tanques/create', 'TanquesController@createTanques')->name('admin.tanques.to_create');
            Route::post('/tanques/store', 'TanquesController@storeTanques')->name('admin.tanques.to_store');
            Route::get('/tanques/{id}/edit', 'TanquesController@editTanques')->name('admin.tanques.to_edit');
            Route::post('/tanques/{id}/update', 'TanquesController@updateTanques')->name('admin.tanques.to_update');
            Route::get('/tanques/{id}/remove', 'TanquesController@removeTanques')->name('admin.tanques.to_remove');
            Route::get('/tanques/{id}/turn', 'TanquesController@turnTanques')->name('admin.tanques.to_turn');
            Route::post('/tanques/tipos/store', 'TanquesTiposController@storeTanquesTipos')->name('admin.tanques.tipos.to_store');

             // Rotas do controlador de sondas laboratoriais
             Route::get('/sondas_laboratoriais', 'SondasLaboratoriaisController@listingSondasLaboratoriais')->name('admin.sondas_laboratoriais');
             Route::any('/sondas_laboratoriais/search', 'SondasLaboratoriaisController@searchSondasLaboratoriais')->name('admin.sondas_laboratoriais.to_search');
             Route::get('/sondas_laboratoriais/create', 'SondasLaboratoriaisController@createSondasLaboratoriais')->name('admin.sondas_laboratoriais.to_create');
             Route::post('/sondas_laboratoriais/store', 'SondasLaboratoriaisController@storeSondasLaboratoriais')->name('admin.sondas_laboratoriais.to_store');
             Route::get('/sondas_laboratoriais/{id}/edit', 'SondasLaboratoriaisController@editSondasLaboratoriais')->name('admin.sondas_laboratoriais.to_edit');
             Route::post('/sondas_laboratoriais/{id}/update', 'SondasLaboratoriaisController@updateSondasLaboratoriais')->name('admin.sondas_laboratoriais.to_update');
             Route::get('/sondas_laboratoriais/{id}/remove', 'SondasLaboratoriaisController@removeSondasLaboratoriais')->name('admin.sondas_laboratoriais.to_remove');
             Route::get('/sondas_laboratoriais/{id}/turn', 'SondasLaboratoriaisController@turnSondasLaboratoriais')->name('admin.sondas_laboratoriais.to_turn');

             // Rotas do controlador de fatores de conversão sondas laboratoriais
             Route::get('/sondas_laboratoriais/{sonda_id}/sondas_fatores', 'SondasFatoresController@listingSondasFatores')->name('admin.sondas_laboratoriais.sondas_fatores');
             Route::any('/sondas_laboratoriais/{sonda_id}/sondas_fatores/search', 'SondasFatoresController@searchSondasFatores')->name('admin.sondas_laboratoriais.sondas_fatores.to_search');
             Route::get('/sondas_laboratoriais/{sonda_id}/sondas_fatores/create', 'SondasFatoresController@createSondasFatores')->name('admin.sondas_laboratoriais.sondas_fatores.to_create');
             Route::post('/sondas_laboratoriais/{sonda_id}/sondas_fatores/store', 'SondasFatoresController@storeSondasFatores')->name('admin.sondas_laboratoriais.sondas_fatores.to_store');
             Route::get('/sondas_laboratoriais/{sonda_id}/sondas_fatores/{id}/edit', 'SondasFatoresController@editSondasFatores')->name('admin.sondas_laboratoriais.sondas_fatores.to_edit');
             Route::post('/sondas_laboratoriais/{sonda_id}/sondas_fatores/{id}/update', 'SondasFatoresController@updateSondasFatores')->name('admin.sondas_laboratoriais.sondas_fatores.to_update');
             Route::get('/sondas_laboratoriais/{sonda_id}/sondas_fatores/{id}/remove', 'SondasFatoresController@removeSondasFatores')->name('admin.sondas_laboratoriais.sondas_fatores.to_remove');
             Route::get('/sondas_laboratoriais/{sonda_id}/sondas_fatores/{id}/turn', 'SondasFatoresController@turnSondasFatores')->name('admin.sondas_laboratoriais.sondas_fatores.to_turn');

             // Rotas do controlador de fatores de conversão
            Route::get('/sondas_fatores', 'SondasFatoresController@listingSondasFatores')->name('admin.sondas_fatores');
            Route::any('/sondas_fatores/search', 'SondasFatoresController@searchSondasFatores')->name('admin.sondas_fatores.to_search');
            Route::get('/sondas_fatores/create', 'SondasFatoresController@createSondasFatores')->name('admin.sondas_fatores.to_create');
            Route::post('/sondas_fatores/store', 'SondasFatoresController@storeSondasFatores')->name('admin.sondas_fatores.to_store');
            Route::get('/sondas_fatores/{id}/edit', 'SondasFatoresController@editSondasFatores')->name('admin.sondas_fatores.to_edit');
            Route::post('/sondas_fatores/{id}/update', 'SondasFatoresController@updateSondasFatores')->name('admin.sondas_fatores.to_update');
            Route::get('/sondas_fatores/{id}/remove', 'SondasFatoresController@removeSondasFatores')->name('admin.sondas_fatores.to_remove');
            Route::get('/sondas_fatores/{id}/turn', 'SondasFatoresController@turnSondasFatores')->name('admin.sondas_fatores.to_turn');
             
    
            // Rotas do controlador de ciclos
            Route::get('/ciclos', 'CiclosController@listingCiclos')->name('admin.ciclos');
            Route::any('/ciclos/search', 'CiclosController@searchCiclos')->name('admin.ciclos.to_search');
            Route::get('/ciclos/create', 'CiclosController@createCiclos')->name('admin.ciclos.to_create');
            Route::post('/ciclos/store', 'CiclosController@storeCiclos')->name('admin.ciclos.to_store');
            Route::get('/ciclos/{id}/edit', 'CiclosController@editCiclos')->name('admin.ciclos.to_edit');
            Route::post('/ciclos/{id}/update', 'CiclosController@updateCiclos')->name('admin.ciclos.to_update');
            Route::get('/ciclos/{id}/remove', 'CiclosController@removeCiclos')->name('admin.ciclos.to_remove');
    
            // Rotas do controlador de transferencias de animais
            Route::get('/transferencias_animais', 'TransferenciasAnimaisController@listing')->name('admin.transferencias_animais');
            Route::any('/transferencias_animais/search', 'TransferenciasAnimaisController@search')->name('admin.transferencias_animais.to_search');
            Route::get('/transferencias_animais/create', 'TransferenciasAnimaisController@create')->name('admin.transferencias_animais.to_create');
            Route::post('/transferencias_animais/store', 'TransferenciasAnimaisController@store')->name('admin.transferencias_animais.to_store');
            Route::get('/transferencias_animais/{id}/remove', 'TransferenciasAnimaisController@remove')->name('admin.transferencias_animais.to_remove');
    
            // Rotas do controlador de produtos
            Route::get('/produtos', 'ProdutosController@listingProdutos')->name('admin.produtos');
            Route::any('/produtos/search', 'ProdutosController@searchProdutos')->name('admin.produtos.to_search');
            Route::get('/produtos/create', 'ProdutosController@createProdutos')->name('admin.produtos.to_create');
            Route::post('/produtos/store', 'ProdutosController@storeProdutos')->name('admin.produtos.to_store');
            Route::get('/produtos/{id}/edit', 'ProdutosController@editProdutos')->name('admin.produtos.to_edit');
            Route::post('/produtos/{id}/update', 'ProdutosController@updateProdutos')->name('admin.produtos.to_update');
            Route::get('/produtos/{id}/remove', 'ProdutosController@removeProdutos')->name('admin.produtos.to_remove');
            Route::get('/produtos/{id}/turn', 'ProdutosController@turnProdutos')->name('admin.produtos.to_turn');
            Route::post('/produtos/tipos/store', 'ProdutosTiposController@storeProdutosTipos')->name('admin.produtos.tipos.to_store');
    
            // Rotas do controlador de clientes e fornecedores
            Route::get('/clientes_fornecedores', 'ClientesFornecedoresController@listingClientesFornecedores')->name('admin.clientes_fornecedores');
            Route::any('/clientes_fornecedores/search', 'ClientesFornecedoresController@searchClientesFornecedores')->name('admin.clientes_fornecedores.to_search');
            Route::get('/clientes_fornecedores/create', 'ClientesFornecedoresController@createClientesFornecedores')->name('admin.clientes_fornecedores.to_create');
            Route::post('/clientes_fornecedores/store', 'ClientesFornecedoresController@storeClientesFornecedores')->name('admin.clientes_fornecedores.to_store');
            Route::get('/clientes_fornecedores/{id}/edit', 'ClientesFornecedoresController@editClientesFornecedores')->name('admin.clientes_fornecedores.to_edit');
            Route::post('/clientes_fornecedores/{id}/update', 'ClientesFornecedoresController@updateClientesFornecedores')->name('admin.clientes_fornecedores.to_update');
            Route::get('/clientes_fornecedores/{id}/remove', 'ClientesFornecedoresController@removeClientesFornecedores')->name('admin.clientes_fornecedores.to_remove');
    
            // Rotas do controlador de módulos
            Route::get('/modulos', 'ModulosController@listingModulos')->name('admin.modulos');
            Route::any('/modulos/search', 'ModulosController@searchModulos')->name('admin.modulos.to_search');
            Route::get('/modulos/create', 'ModulosController@createModulos')->name('admin.modulos.to_create');
            Route::post('/modulos/store', 'ModulosController@storeModulos')->name('admin.modulos.to_store');
            Route::get('/modulos/{id}/edit', 'ModulosController@editModulos')->name('admin.modulos.to_edit');
            Route::post('/modulos/{id}/update', 'ModulosController@updateModulos')->name('admin.modulos.to_update');
            Route::get('/modulos/{id}/remove', 'ModulosController@removeModulos')->name('admin.modulos.to_remove');
    
            // Rotas do controlador de menus
            Route::get('/modulos/{modulo_id}/menus', 'MenusController@listingMenus')->name('admin.modulos.menus');
            Route::any('/modulos/{modulo_id}/menus/search', 'MenusController@searchMenus')->name('admin.modulos.menus.to_search');
            Route::get('/modulos/{modulo_id}/menus/create', 'MenusController@createMenus')->name('admin.modulos.menus.to_create');
            Route::post('/modulos/{modulo_id}/menus/store', 'MenusController@storeMenus')->name('admin.modulos.menus.to_store');
            Route::get('/modulos/{modulo_id}/menus/{id}/edit', 'MenusController@editMenus')->name('admin.modulos.menus.to_edit');
            Route::post('/modulos/{modulo_id}/menus/{id}/update', 'MenusController@updateMenus')->name('admin.modulos.menus.to_update');
            Route::get('/modulos/{modulo_id}/menus/{id}/remove', 'MenusController@removeMenus')->name('admin.modulos.menus.to_remove');
            Route::get('/modulos/{modulo_id}/menus/{id}/turn', 'MenusController@turnMenus')->name('admin.modulos.menus.to_turn');
    
            // Rotas do controlador de submenus
            
            Route::get('/modulos/{modulo_id}/menus/{menu_id}/submenus', 'SubmenusController@listingSubmenus')->name('admin.modulos.submenus');
            Route::any('/modulos/{modulo_id}/menus/{menu_id}/submenus/search', 'SubmenusController@searchSubmenus')->name('admin.modulos.submenus.to_search');
            Route::get('/modulos/{modulo_id}/menus/{menu_id}/submenus/create', 'SubmenusController@createSubmenus')->name('admin.modulos.submenus.to_create');
            Route::post('/modulos/{modulo_id}/menus/{menu_id}/submenus/store', 'SubmenusController@storeSubmenus')->name('admin.modulos.submenus.to_store');
            Route::get('/modulos/{modulo_id}/menus/{menu_id}/submenus/{id}/edit', 'SubmenusController@editSubmenus')->name('admin.modulos.submenus.to_edit');
            Route::post('/modulos/{modulo_id}/menus/{menu_id}/submenus/{id}/update', 'SubmenusController@updateSubmenus')->name('admin.modulos.submenus.to_update');
            Route::get('/modulos/{modulo_id}/menus/{menu_id}/submenus/{id}/remove', 'SubmenusController@removeSubmenus')->name('admin.modulos.submenus.to_remove');
            Route::get('/modulos/{modulo_id}/menus/{menu_id}/submenus/{id}/turn', 'SubmenusController@turnSubmenus')->name('admin.modulos.submenus.to_turn');
    
            // Rotas do controlador de taxas e custos
            Route::get('/taxas_custos', 'TaxasCustosController@listingTaxasCustos')->name('admin.taxas_custos');
            Route::any('/taxas_custos/search', 'TaxasCustosController@searchTaxasCustos')->name('admin.taxas_custos.to_search');
            Route::get('/taxas_custos/create', 'TaxasCustosController@createTaxasCustos')->name('admin.taxas_custos.to_create');
            Route::post('/taxas_custos/store', 'TaxasCustosController@storeTaxasCustos')->name('admin.taxas_custos.to_store');
            Route::get('/taxas_custos/{id}/edit', 'TaxasCustosController@editTaxasCustos')->name('admin.taxas_custos.to_edit');
            Route::post('/taxas_custos/{id}/update', 'TaxasCustosController@updateTaxasCustos')->name('admin.taxas_custos.to_update');
            Route::get('/taxas_custos/{id}/remove', 'TaxasCustosController@removeTaxasCustos')->name('admin.taxas_custos.to_remove');
    
            // Rotas do controlador de analises presuntivas
            Route::get('/presuntivas', 'PresuntivasController@listingPresuntiva')->name('admin.presuntivas');
            Route::any('/presuntivas/search', 'PresuntivasController@searchPresuntiva')->name('admin.presuntivas.to_search');
            Route::get('/presuntivas/create', 'PresuntivasController@createPresuntiva')->name('admin.presuntivas.to_create');
            Route::post('/presuntivas/store', 'PresuntivasController@storePresuntiva')->name('admin.presuntivas.to_store');
            Route::post('/presuntivas/temp', 'PresuntivasController@storeTempPresuntiva')->name('admin.presuntivas.to_temp');
            Route::get('/presuntivas/{id}/edit', 'PresuntivasController@editPresuntiva')->name('admin.presuntivas.to_edit');
            Route::post('/presuntivas/{id}/update', 'PresuntivasController@updatePresuntiva')->name('admin.presuntivas.to_update');
            Route::get('/presuntivas/{id}/remove', 'PresuntivasController@removePresuntiva')->name('admin.presuntivas.to_remove');
            // Rotas do controlador de coleta de amostras de análises presutivas
            Route::get('/presuntivas/{presuntiva_id}/amostras', 'PresuntivasController@createPresuntivaAmostras')->name('admin.presuntivas.amostras.to_create');
            Route::post('/presuntivas/{presuntiva_id}/amostras/store', 'PresuntivasController@storePresuntivaAmostras')->name('admin.presuntivas.amostras.to_store');
        
             // Rotas do controlador de parametros
            Route::get('/coleta_parametros_tipos', 'ColetasParametrosTiposController@listingColetasParametrosTipos')->name('admin.coletas_parametros_tipos');
            Route::any('/coleta_parametros_tipos/search', 'ColetasParametrosTiposController@searchColetasParametrosTipos')->name('admin.coletas_parametros_tipos.to_search');
            Route::get('/coleta_parametros_tipos/create', 'ColetasParametrosTiposController@createColetasParametrosTipos')->name('admin.coletas_parametros_tipos.to_create');
            Route::post('/coleta_parametros_tipos/store', 'ColetasParametrosTiposController@storeColetasParametrosTipos')->name('admin.coletas_parametros_tipos.to_store');
            Route::get('/coleta_parametros_tipos/{id}/edit', 'ColetasParametrosTiposController@editColetasParametrosTipos')->name('admin.coletas_parametros_tipos.to_edit');
            Route::post('/coleta_parametros_tipos/{id}/update', 'ColetasParametrosTiposController@updateColetasParametrosTipos')->name('admin.coletas_parametros_tipos.to_update');
            Route::get('/coleta_parametros_tipos/{id}/remove', 'ColetasParametrosTiposController@removeColetasParametrosTipos')->name('admin.coletas_parametros_tipos.to_remove');
            Route::get('/coleta_parametros_tipos/{id}/turn', 'ColetasParametrosTiposController@turnColetasParametrosTipos')->name('admin.coletas_parametros_tipos.to_turn');
            Route::get('/coleta_parametros_tipos/{id}/alertas/turn', 'ColetasParametrosTiposController@turnColetasParametrosTiposAlertas')->name('admin.coletas_parametros_tipos.alertas.to_turn');
    
            // Rotas do controlador de coleta de parametros
            Route::get('/coleta_parametros', 'ColetasParametrosController@listingColetaParametros')->name('admin.coletas_parametros');
            Route::any('/coleta_parametros/search', 'ColetasParametrosController@searchColetaParametros')->name('admin.coletas_parametros.to_search');
            Route::get('/coleta_parametros/create', 'ColetasParametrosController@createColetaParametros')->name('admin.coletas_parametros.to_create');
            Route::post('/coleta_parametros/store', 'ColetasParametrosController@storeColetaParametros')->name('admin.coletas_parametros.to_store');
            Route::post('/coleta_parametros/temp', 'ColetasParametrosController@storeTempColetaParametros')->name('admin.coletas_parametros.to_temp');
            Route::get('/coleta_parametros/{id}/edit', 'ColetasParametrosController@editColetaParametros')->name('admin.coletas_parametros.to_edit');
            Route::post('/coleta_parametros/{id}/update', 'ColetasParametrosController@updateColetaParametros')->name('admin.coletas_parametros.to_update');
            Route::get('/coleta_parametros/{id}/remove', 'ColetasParametrosController@removeColetaParametros')->name('admin.coletas_parametros.to_remove');
            // Rotas do controlador de coleta de amostras de análises presutivas
            Route::get('/coleta_parametros/{id}/amostras', 'ColetasParametrosController@createColetaParametrosAmostras')->name('admin.coletas_parametros.amostras.to_create');
            Route::post('/coleta_parametros/{id}/amostras/store', 'ColetasParametrosController@storeColetaParametrosAmostras')->name('admin.coletas_parametros.amostras.to_store');
    
            // Novo layout do cadastro de coletas de parametros
            Route::get('/coleta_parametros_new', 'ColetasParametrosNewController@listing')->name('admin.coletas_parametros_new');
            Route::any('/coleta_parametros_new/search', 'ColetasParametrosNewController@search')->name('admin.coletas_parametros_new.to_search');
            Route::get('/coleta_parametros_new/create', 'ColetasParametrosNewController@create')->name('admin.coletas_parametros_new.to_create');
            Route::post('/coleta_parametros_new/store', 'ColetasParametrosNewController@store')->name('admin.coletas_parametros_new.to_store');
            Route::get('/coleta_parametros_new/{id}/edit', 'ColetasParametrosNewController@edit')->name('admin.coletas_parametros_new.to_edit');
            Route::post('/coleta_parametros_new/{id}/update', 'ColetasParametrosNewController@update')->name('admin.coletas_parametros_new.to_update');
            Route::get('/coleta_parametros_new/{id}/remove', 'ColetasParametrosNewController@remove')->name('admin.coletas_parametros_new.to_remove');

            Route::get('/coleta_parametros_new/{coleta_parametro_id}/amostras/create', 'ColetasParametrosAmostrasNewController@create')->name('admin.coletas_parametros_new.amostras.to_create');
            Route::post('/coleta_parametros_new/{coleta_parametro_id}/amostras/store', 'ColetasParametrosAmostrasNewController@store')->name('admin.coletas_parametros_new.amostras.to_store');
            Route::get('/coleta_parametros_new/{coleta_parametro_id}/amostras/{id}/remove', 'ColetasParametrosAmostrasNewController@remove')->name('admin.coletas_parametros_new.amostras.to_remove');
    
            // Rotas do controlador de importacoes de coletas de parâmetros
            Route::get('/coletas_parametros_importacoes', 'ColetasParametrosImportacoesController@listingColetasParametrosImportacoes')->name('admin.coletas_parametros_importacoes');
            Route::any('/coletas_parametros_importacoes/search', 'ColetasParametrosImportacoesController@searchColetasParametrosImportacoes')->name('admin.coletas_parametros_importacoes.to_search');
            Route::get('/coletas_parametros_importacoes/{id}/remove', 'ColetasParametrosImportacoesController@removeColetasParametrosImportacoes')->name('admin.coletas_parametros_importacoes.to_remove');
            Route::post('/coletas_parametros_importacoes/import', 'ColetasParametrosImportacoesController@importColetasParametrosImportacoes')->name('admin.coletas_parametros_importacoes.to_import');

            // Rotas do controlador de analises de planctôns
            Route::get('/planctons', 'PlanctonsController@listingPlancton')->name('admin.planctons');
            Route::any('/planctons/search', 'PlanctonsController@searchPlancton')->name('admin.planctons.to_search');
            Route::get('/planctons/create', 'PlanctonsController@createPlancton')->name('admin.planctons.to_create');
            Route::post('/planctons/store', 'PlanctonsController@storePlancton')->name('admin.planctons.to_store');
            Route::post('/planctons/temp', 'PlanctonsController@storeTempPlancton')->name('admin.planctons.to_temp');
            Route::get('/planctons/{id}/edit', 'PlanctonsController@editPlancton')->name('admin.planctons.to_edit');
            Route::post('/planctons/{id}/update', 'PlanctonsController@updatePlancton')->name('admin.planctons.to_update');
            Route::get('/planctons/{id}/remove', 'PlanctonsController@removePlancton')->name('admin.planctons.to_remove');
            // Rotas do controlador de coleta de amostras de análises planctôns
            Route::get('/planctons/{plancton_id}/amostras', 'PlanctonsController@createPlanctonAmostras')->name('admin.planctons.amostras.to_create');
            Route::post('/planctons/{plancton_id}/amostras/store', 'PlanctonsController@storePlanctonAmostras')->name('admin.planctons.amostras.to_store');
    
            // Rotas do controlador de preparacoes
            Route::get('/preparacoes_tipos', 'PreparacoesTiposController@listingPreparacoesTipos')->name('admin.preparacoes_tipos');
            Route::any('/preparacoes_tipos/search', 'PreparacoesTiposController@searchPreparacoesTipos')->name('admin.preparacoes_tipos.to_search');
            Route::get('/preparacoes_tipos/create', 'PreparacoesTiposController@createPreparacoesTipos')->name('admin.preparacoes_tipos.to_create');
            Route::post('/preparacoes_tipos/store', 'PreparacoesTiposController@storePreparacoesTipos')->name('admin.preparacoes_tipos.to_store');
            Route::get('/preparacoes_tipos/{id}/edit', 'PreparacoesTiposController@editPreparacoesTipos')->name('admin.preparacoes_tipos.to_edit');
            Route::post('/preparacoes_tipos/{id}/update', 'PreparacoesTiposController@updatePreparacoesTipos')->name('admin.preparacoes_tipos.to_update');
            Route::get('/preparacoes_tipos/{id}/remove', 'PreparacoesTiposController@removePreparacoesTipos')->name('admin.preparacoes_tipos.to_remove');
            Route::get('/preparacoes_tipos/{id}/turn', 'PreparacoesTiposController@turnPreparacoesTipos')->name('admin.preparacoes_tipos.to_turn');
    
            // Rotas do controlador de preparacoes
            Route::get('/preparacoes', 'PreparacoesController@listingPreparacoes')->name('admin.preparacoes');
            Route::any('/preparacoes/search', 'PreparacoesController@searchPreparacoes')->name('admin.preparacoes.to_search');
            Route::get('/preparacoes/create', 'PreparacoesController@createPreparacoes')->name('admin.preparacoes.to_create');
            Route::post('/preparacoes/store', 'PreparacoesController@storePreparacoes')->name('admin.preparacoes.to_store');
            Route::get('/preparacoes/{id}/edit', 'PreparacoesController@editPreparacoes')->name('admin.preparacoes.to_edit');
            Route::post('/preparacoes/{id}/update', 'PreparacoesController@updatePreparacoes')->name('admin.preparacoes.to_update');
            Route::get('/preparacoes/{id}/remove', 'PreparacoesController@removePreparacoes')->name('admin.preparacoes.to_remove');
            Route::post('/preparacoes/{id}/turn', 'PreparacoesController@turnPreparacoes')->name('admin.preparacoes.to_turn'); // Esta rota "turn" em específico, deve ser do tipo "post"
            Route::get('/preparacoes/{id}/view', 'PreparacoesController@viewPreparacoes')->name('admin.preparacoes.to_view');
            Route::get('/preparacoes/{id}/close', 'PreparacoesController@closePreparacoes')->name('admin.preparacoes.to_close');
    
            // Rotas do controlador de aplicações das preparacoes
            Route::get('/preparacoes/{preparacao_id}/aplicacoes', 'PreparacoesAplicacoesController@listingPreparacoesAplicacoes')->name('admin.preparacoes.aplicacoes');
            Route::any('/preparacoes/{preparacao_id}/aplicacoes/search', 'PreparacoesAplicacoesController@searchPreparacoesAplicacoes')->name('admin.preparacoes.aplicacoes.to_search');
            Route::get('/preparacoes/{preparacao_id}/aplicacoes/create', 'PreparacoesAplicacoesController@createPreparacoesAplicacoes')->name('admin.preparacoes.aplicacoes.to_create');
            Route::post('/preparacoes/{preparacao_id}/aplicacoes/store', 'PreparacoesAplicacoesController@storePreparacoesAplicacoes')->name('admin.preparacoes.aplicacoes.to_store');
            Route::post('/preparacoes/{preparacao_id}/aplicacoes/{id}/reverse', 'PreparacoesAplicacoesController@reversePreparacoesAplicacoes')->name('admin.preparacoes.aplicacoes.to_reverse');
    
            // Rotas do controlador de preparacoes (funcionalidade para teste)
            Route::get('/preparacoes_v2', 'PreparacoesV2Controller@listingPreparacoes')->name('admin.preparacoes_v2');
            Route::any('/preparacoes_v2/search', 'PreparacoesV2Controller@searchPreparacoes')->name('admin.preparacoes_v2.to_search');
            Route::get('/preparacoes_v2/create', 'PreparacoesV2Controller@createPreparacoes')->name('admin.preparacoes_v2.to_create');
            Route::post('/preparacoes_v2/store', 'PreparacoesV2Controller@storePreparacoes')->name('admin.preparacoes_v2.to_store');
            Route::get('/preparacoes_v2/{id}/edit', 'PreparacoesV2Controller@editPreparacoes')->name('admin.preparacoes_v2.to_edit');
            Route::post('/preparacoes_v2/{id}/update', 'PreparacoesV2Controller@updatePreparacoes')->name('admin.preparacoes_v2.to_update');
            Route::get('/preparacoes_v2/{id}/remove', 'PreparacoesV2Controller@removePreparacoes')->name('admin.preparacoes_v2.to_remove');
            Route::post('/preparacoes_v2/{id}/turn', 'PreparacoesV2Controller@turnPreparacoes')->name('admin.preparacoes_v2.to_turn'); // Esta rota "turn" em específico, deve ser do tipo "post"
            Route::get('/preparacoes_v2/{id}/view', 'PreparacoesV2Controller@viewPreparacoes')->name('admin.preparacoes_v2.to_view');
            Route::get('/preparacoes_v2/{id}/close', 'PreparacoesV2Controller@closePreparacoes')->name('admin.preparacoes_v2.to_close');
    
            // Rotas do controlador de aplicações das preparacoes
            Route::get('/preparacoes_v2/{preparacao_id}/aplicacoes', 'PreparacoesV2AplicacoesController@listingPreparacoesAplicacoes')->name('admin.preparacoes_v2.aplicacoes');
            Route::any('/preparacoes_v2/{preparacao_id}/aplicacoes/search', 'PreparacoesV2AplicacoesController@searchPreparacoesAplicacoes')->name('admin.preparacoes_v2.aplicacoes.to_search');
            Route::get('/preparacoes_v2/{preparacao_id}/aplicacoes/create', 'PreparacoesV2AplicacoesController@createPreparacoesAplicacoes')->name('admin.preparacoes_v2.aplicacoes.to_create');
            Route::post('/preparacoes_v2/{preparacao_id}/aplicacoes/store', 'PreparacoesV2AplicacoesController@storePreparacoesAplicacoes')->name('admin.preparacoes_v2.aplicacoes.to_store');
            Route::post('/preparacoes_v2/{preparacao_id}/aplicacoes/{id}/update', 'PreparacoesV2AplicacoesController@updatePreparacoesAplicacoes')->name('admin.preparacoes_v2.aplicacoes.to_update');
            Route::get('/preparacoes_v2/{preparacao_id}/aplicacoes/{id}/remove', 'PreparacoesV2AplicacoesController@removePreparacoesAplicacoes')->name('admin.preparacoes_v2.aplicacoes.to_remove');
    
            // Rotas do controlador de validações das preparações
            Route::get('/preparacoes_v2/{preparacao_id}/validacoes', 'PreparacoesV2ValidacoesController@listingPreparacoesValidacoes')->name('admin.preparacoes_v2.validacoes');
            Route::get('/preparacoes_v2/{preparacao_id}/validacoes/close', 'PreparacoesV2ValidacoesController@closePreparacoesValidacoes')->name('admin.preparacoes_v2.validacoes.to_close');
            Route::post('/preparacoes_v2/{preparacao_id}/validacoes/reverse', 'PreparacoesV2ValidacoesController@reversePreparacoesValidacoes')->name('admin.preparacoes_v2.validacoes.to_reverse');
    
            // Rotas do controlador de perfis de arracoamentos
            Route::get('/arracoamentos_perfis', 'ArracoamentosPerfisController@listingArracoamentosPerfis')->name('admin.arracoamentos_perfis');
            Route::any('/arracoamentos_perfis/search', 'ArracoamentosPerfisController@searchArracoamentosPerfis')->name('admin.arracoamentos_perfis.to_search');
            Route::get('/arracoamentos_perfis/create', 'ArracoamentosPerfisController@createArracoamentosPerfis')->name('admin.arracoamentos_perfis.to_create');
            Route::post('/arracoamentos_perfis/store', 'ArracoamentosPerfisController@storeArracoamentosPerfis')->name('admin.arracoamentos_perfis.to_store');
            Route::get('/arracoamentos_perfis/{id}/edit', 'ArracoamentosPerfisController@editArracoamentosPerfis')->name('admin.arracoamentos_perfis.to_edit');
            Route::post('/arracoamentos_perfis/{id}/update', 'ArracoamentosPerfisController@updateArracoamentosPerfis')->name('admin.arracoamentos_perfis.to_update');
            Route::get('/arracoamentos_perfis/{id}/remove', 'ArracoamentosPerfisController@removeArracoamentosPerfis')->name('admin.arracoamentos_perfis.to_remove');
            Route::get('/arracoamentos_perfis/{id}/turn', 'ArracoamentosPerfisController@turnArracoamentosPerfis')->name('admin.arracoamentos_perfis.to_turn');
    
            // Rotas do controlador de esquemas de perfis de arracoamentos
            Route::get('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas', 'ArracoamentosEsquemasController@listingArracoamentosEsquemas')->name('admin.arracoamentos_perfis.arracoamentos_esquemas');
            Route::any('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/search', 'ArracoamentosEsquemasController@searchArracoamentosEsquemas')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.to_search');
            Route::get('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/create', 'ArracoamentosEsquemasController@createArracoamentosEsquemas')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.to_create');
            Route::post('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/store', 'ArracoamentosEsquemasController@storeArracoamentosEsquemas')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.to_store');
            Route::get('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/{id}/edit', 'ArracoamentosEsquemasController@editArracoamentosEsquemas')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.to_edit');
            Route::post('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/{id}/update', 'ArracoamentosEsquemasController@updateArracoamentosEsquemas')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.to_update');
            Route::get('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/{id}/remove', 'ArracoamentosEsquemasController@removeArracoamentosEsquemas')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.to_remove');
    
            // Rotas do controlador do registro de racoes e periodos de alimentacao dos esquemas
            Route::get('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/{arracoamento_esquema_id}/arracoamentos_esquemas_itens', 'ArracoamentosEsquemasItensController@listingArracoamentosEsquemasItens')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_esquemas_itens');
            Route::post('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/{arracoamento_esquema_id}/arracoamentos_racoes/store', 'ArracoamentosRacoesController@storeArracoamentosRacoes')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_racoes.to_store');
            Route::get('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/{arracoamento_esquema_id}/arracoamentos_racoes/{id}/remove', 'ArracoamentosRacoesController@removeArracoamentosRacoes')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_racoes.to_remove');
            Route::post('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/{arracoamento_esquema_id}/arracoamentos_alimentacoes/store', 'ArracoamentosAlimentacoesController@storeArracoamentosAlimentacoes')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_alimentacoes.to_store');
            Route::get('/arracoamentos_perfis/{arracoamento_perfil_id}/arracoamentos_esquemas/{arracoamento_esquema_id}/arracoamentos_alimentacoes/{id}/remove', 'ArracoamentosAlimentacoesController@removeArracoamentosAlimentacoes')->name('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_alimentacoes.to_remove');
    
            // Rotas do controlador de receitas laboratoriais
            Route::get('/receitas_laboratoriais', 'ReceitasLaboratoriaisController@listingReceitasLaboratoriais')->name('admin.receitas_laboratoriais');
            Route::any('/receitas_laboratoriais/search', 'ReceitasLaboratoriaisController@searchReceitasLaboratoriais')->name('admin.receitas_laboratoriais.to_search');
            Route::get('/receitas_laboratoriais/create', 'ReceitasLaboratoriaisController@createReceitasLaboratoriais')->name('admin.receitas_laboratoriais.to_create');
            Route::post('/receitas_laboratoriais/store', 'ReceitasLaboratoriaisController@storeReceitasLaboratoriais')->name('admin.receitas_laboratoriais.to_store');
            Route::get('/receitas_laboratoriais/{id}/edit', 'ReceitasLaboratoriaisController@editReceitasLaboratoriais')->name('admin.receitas_laboratoriais.to_edit');
            Route::post('/receitas_laboratoriais/{id}/update', 'ReceitasLaboratoriaisController@updateReceitasLaboratoriais')->name('admin.receitas_laboratoriais.to_update');
            Route::get('/receitas_laboratoriais/{id}/remove', 'ReceitasLaboratoriaisController@removeReceitasLaboratoriais')->name('admin.receitas_laboratoriais.to_remove');
            Route::get('/receitas_laboratoriais/{id}/turn', 'ReceitasLaboratoriaisController@turnReceitasLaboratoriais')->name('admin.receitas_laboratoriais.to_turn');
            Route::post('/receitas_laboratoriais/tipos/store', 'ReceitasLaboratoriaisTiposController@storeReceitasLaboratoriaisTipos')->name('admin.receitas_laboratoriais.tipos.to_store');
    
            // Rotas do controlador de periodos de utilização das receitas laboratoriais
            Route::get('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos', 'ReceitasLaboratoriaisPeriodosController@listingReceitasLaboratoriaisPeriodos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos');
            Route::any('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/search', 'ReceitasLaboratoriaisPeriodosController@searchReceitasLaboratoriaisPeriodos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_search');
            Route::get('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/create', 'ReceitasLaboratoriaisPeriodosController@createReceitasLaboratoriaisPeriodos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_create');
            Route::post('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/store', 'ReceitasLaboratoriaisPeriodosController@storeReceitasLaboratoriaisPeriodos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_store');
            Route::get('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/{id}/edit', 'ReceitasLaboratoriaisPeriodosController@editReceitasLaboratoriaisPeriodos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_edit');
            Route::post('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/{id}/update', 'ReceitasLaboratoriaisPeriodosController@updateReceitasLaboratoriaisPeriodos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_update');
            Route::get('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/{id}/remove', 'ReceitasLaboratoriaisPeriodosController@removeReceitasLaboratoriaisPeriodos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.to_remove');
    
            // Rotas do controlador de produtos das receitas laboratoriais
            Route::get('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/{receita_laboratorial_periodo_id}/receitas_laboratoriais_produtos', 'ReceitasLaboratoriaisProdutosController@listingReceitasLaboratoriaisProdutos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos');
            Route::any('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/{receita_laboratorial_periodo_id}/receitas_laboratoriais_produtos/search', 'ReceitasLaboratoriaisProdutosController@searchReceitasLaboratoriaisProdutos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos.to_search');
            Route::get('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/{receita_laboratorial_periodo_id}/receitas_laboratoriais_produtos/create', 'ReceitasLaboratoriaisProdutosController@createReceitasLaboratoriaisProdutos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos.to_create');
            Route::post('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/{receita_laboratorial_periodo_id}/receitas_laboratoriais_produtos/store', 'ReceitasLaboratoriaisProdutosController@storeReceitasLaboratoriaisProdutos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos.to_store');
            Route::get('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/{receita_laboratorial_periodo_id}/receitas_laboratoriais_produtos/{id}/edit', 'ReceitasLaboratoriaisProdutosController@editReceitasLaboratoriaisProdutos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos.to_edit');
            Route::post('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/{receita_laboratorial_periodo_id}/receitas_laboratoriais_produtos/{id}/update', 'ReceitasLaboratoriaisProdutosController@updateReceitasLaboratoriaisProdutos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos.to_update');
            Route::get('/receitas_laboratoriais/{receita_laboratorial_id}/receitas_laboratoriais_periodos/{receita_laboratorial_periodo_id}/receitas_laboratoriais_produtos/{id}/remove', 'ReceitasLaboratoriaisProdutosController@removeReceitasLaboratoriaisProdutos')->name('admin.receitas_laboratoriais.receitas_laboratoriais_periodos.receitas_laboratoriais_produtos.to_remove');
    
            // Rotas do controlador de notas de entrada
            Route::get('/notas_fiscais_entradas', 'NotasFiscaisEntradasController@listingNotasFiscaisEntradas')->name('admin.notas_fiscais_entradas');
            Route::any('/notas_fiscais_entradas/search', 'NotasFiscaisEntradasController@searchNotasFiscaisEntradas')->name('admin.notas_fiscais_entradas.to_search');
            Route::get('/notas_fiscais_entradas/create', 'NotasFiscaisEntradasController@createNotasFiscaisEntradas')->name('admin.notas_fiscais_entradas.to_create');
            Route::post('/notas_fiscais_entradas/store', 'NotasFiscaisEntradasController@storeNotasFiscaisEntradas')->name('admin.notas_fiscais_entradas.to_store');
            Route::get('/notas_fiscais_entradas/{id}/edit', 'NotasFiscaisEntradasController@editNotasFiscaisEntradas')->name('admin.notas_fiscais_entradas.to_edit');
            Route::post('/notas_fiscais_entradas/{id}/update', 'NotasFiscaisEntradasController@updateNotasFiscaisEntradas')->name('admin.notas_fiscais_entradas.to_update');
            Route::get('/notas_fiscais_entradas/{id}/remove', 'NotasFiscaisEntradasController@removeNotasFiscaisEntradas')->name('admin.notas_fiscais_entradas.to_remove');
            Route::get('/notas_fiscais_entradas/{id}/view', 'NotasFiscaisEntradasController@viewNotasFiscaisEntradas')->name('admin.notas_fiscais_entradas.to_view');
            Route::get('/notas_fiscais_entradas/{id}/close', 'NotasFiscaisEntradasController@closeNotasFiscaisEntradas')->name('admin.notas_fiscais_entradas.to_close');
            Route::get('/notas_fiscais_entradas/{id}/reverse', 'NotasFiscaisEntradasController@reverseNotasFiscaisEntradas')->name('admin.notas_fiscais_entradas.to_reverse');
            Route::get('/notas_fiscais_entradas/create/redirectTo/lotes/create', 'NotasFiscaisEntradasController@redirectToCreateLotes')->name('admin.notas_fiscais_entradas.redirect_to.lotes.to_create');
    
            // Rotas do controlador de itens de notas de entrada
            Route::get('/notas_fiscais_entradas/{nota_fiscal_id}/notas_fiscais_itens', 'NotasFiscaisItensController@listingNotasFiscaisItens')->name('admin.notas_fiscais_entradas.notas_fiscais_itens');
            Route::any('/notas_fiscais_entradas/{nota_fiscal_id}/notas_fiscais_itens/search', 'NotasFiscaisItensController@searchNotasFiscaisItens')->name('admin.notas_fiscais_entradas.notas_fiscais_itens.to_search');
            Route::get('/notas_fiscais_entradas/{nota_fiscal_id}/notas_fiscais_itens/create', 'NotasFiscaisItensController@createNotasFiscaisItens')->name('admin.notas_fiscais_entradas.notas_fiscais_itens.to_create');
            Route::post('/notas_fiscais_entradas/{nota_fiscal_id}/notas_fiscais_itens/store', 'NotasFiscaisItensController@storeNotasFiscaisItens')->name('admin.notas_fiscais_entradas.notas_fiscais_itens.to_store');
            Route::get('/notas_fiscais_entradas/{nota_fiscal_id}/notas_fiscais_itens/{id}/edit', 'NotasFiscaisItensController@editNotasFiscaisItens')->name('admin.notas_fiscais_entradas.notas_fiscais_itens.to_edit');
            Route::post('/notas_fiscais_entradas/{nota_fiscal_id}/notas_fiscais_itens/{id}/update', 'NotasFiscaisItensController@updateNotasFiscaisItens')->name('admin.notas_fiscais_entradas.notas_fiscais_itens.to_update');
            Route::get('/notas_fiscais_entradas/{nota_fiscal_id}/notas_fiscais_itens/{id}/remove', 'NotasFiscaisItensController@removeNotasFiscaisItens')->name('admin.notas_fiscais_entradas.notas_fiscais_itens.to_remove');
            Route::get('/notas_fiscais_entradas/{nota_fiscal_id}/notas_fiscais_itens/{id}/view', 'NotasFiscaisItensController@viewNotasFiscaisItens')->name('admin.notas_fiscais_entradas.notas_fiscais_itens.to_view');
            
            // Rotas do controlador de notas de entrada
            Route::get('/notas_fiscais_estornos', 'NotasFiscaisEstornosController@listingNotasFiscaisEstornos')->name('admin.notas_fiscais_estornos');
            Route::any('/notas_fiscais_estornos/search', 'NotasFiscaisEstornosController@searchNotasFiscaisEstornos')->name('admin.notas_fiscais_estornos.to_search');
            Route::get('/notas_fiscais_estornos/{id}/view', 'NotasFiscaisEstornosController@viewNotasFiscaisEstornos')->name('admin.notas_fiscais_estornos.to_view');
    
            // Rotas do controlador de itens de notas estornadas
            Route::get('/notas_fiscais_estornos/{nota_fiscal_id}/notas_fiscais_itens', 'NotasFiscaisItensController@listingNotasFiscaisItens')->name('admin.notas_fiscais_estornos.notas_fiscais_itens');
            Route::any('/notas_fiscais_estornos/{nota_fiscal_id}/notas_fiscais_itens/search', 'NotasFiscaisItensController@searchNotasFiscaisItens')->name('admin.notas_fiscais_estornos.notas_fiscais_itens.to_search');
            Route::get('/notas_fiscais_estornos/{nota_fiscal_id}/notas_fiscais_itens/{id}/remove', 'NotasFiscaisItensController@removeNotasFiscaisItens')->name('admin.notas_fiscais_estornos.notas_fiscais_itens.to_remove');
            Route::get('/notas_fiscais_estornos/{nota_fiscal_id}/notas_fiscais_itens/{id}/view', 'NotasFiscaisItensController@viewNotasFiscaisItens')->name('admin.notas_fiscais_estornos.notas_fiscais_itens.to_view');
        
            // Rotas do controlador de Lançamentos Pendentes
            Route::get('/validacoes_pendentes', 'ValidacoesPendentesController@listingValidacoesPendentes')->name('admin.validacoes_pendentes');
            Route::any('/validacoes_pendentes/search', 'ValidacoesPendentesController@searchValidacoesPendentes')->name('admin.validacoes_pendentes.to_search');
            
            // Rotas do controlador de disponibilidade de estoque
            Route::get('/estoque_disponivel', 'EstoqueDisponivelController@listingEstoqueDisponivel')->name('admin.estoque_disponivel');
            Route::any('/estoque_disponivel/search', 'EstoqueDisponivelController@searchEstoqueDisponivel')->name('admin.estoque_disponivel.to_search');
    
            // Rotas do controlador de entradas de estoque
            Route::get('/estoque_entradas', 'EstoqueEntradasController@listingEstoqueEntradas')->name('admin.estoque_entradas');
            Route::any('/estoque_entradas/search', 'EstoqueEntradasController@searchEstoqueEntradas')->name('admin.estoque_entradas.to_search');
    
            // Rotas do controlador de saídas de estoque
            Route::get('/estoque_saidas', 'EstoqueSaidasController@listingEstoqueSaidas')->name('admin.estoque_saidas');
            Route::any('/estoque_saidas/search', 'EstoqueSaidasController@searchEstoqueSaidas')->name('admin.estoque_saidas.to_search');
    
            // Rotas do controlador de estornos de estoque
            Route::get('/estoque_estornos', 'EstoqueEstornosController@listingEstoqueEstornos')->name('admin.estoque_estornos');
            Route::any('/estoque_estornos/search', 'EstoqueEstornosController@searchEstoqueEstornos')->name('admin.estoque_estornos.to_search');
            Route::get('/estoque_estornos/{estorno_justificativa_id}/produtos', 'EstoqueEstornosController@listingEstoqueEstornosProdutos')->name('admin.estoque_estornos.produtos');
            Route::any('/estoque_estornos/{estorno_justificativa_id}/produtos/search', 'EstoqueEstornosController@searchEstoqueEstornosProdutos')->name('admin.estoque_estornos.produtos.to_search');
    
            // Rotas do controlador de saídas avulsas
            Route::get('/saidas_avulsas', 'SaidasAvulsasController@listingSaidasAvulsas')->name('admin.saidas_avulsas');
            Route::any('/saidas_avulsas/search', 'SaidasAvulsasController@searchSaidasAvulsas')->name('admin.saidas_avulsas.to_search');
            Route::get('/saidas_avulsas/create', 'SaidasAvulsasController@createSaidasAvulsas')->name('admin.saidas_avulsas.to_create');
            Route::post('/saidas_avulsas/store', 'SaidasAvulsasController@storeSaidasAvulsas')->name('admin.saidas_avulsas.to_store');
            Route::get('/saidas_avulsas/{id}/edit', 'SaidasAvulsasController@editSaidasAvulsas')->name('admin.saidas_avulsas.to_edit');
            Route::post('/saidas_avulsas/{id}/update', 'SaidasAvulsasController@updateSaidasAvulsas')->name('admin.saidas_avulsas.to_update');
            Route::get('/saidas_avulsas/{id}/remove', 'SaidasAvulsasController@removeSaidasAvulsas')->name('admin.saidas_avulsas.to_remove');
            Route::get('/saidas_avulsas/{id}/view', 'SaidasAvulsasController@viewSaidasAvulsas')->name('admin.saidas_avulsas.to_view');
    
            // Rotas do controlador dos produtos de saídas avulsas
            Route::get('/saidas_avulsas/{saida_avulsa_id}/produtos', 'SaidasAvulsasProdutosController@listingSaidasAvulsasProdutos')->name('admin.saidas_avulsas.produtos');
            Route::any('/saidas_avulsas/{saida_avulsa_id}/produtos/search', 'SaidasAvulsasProdutosController@searchSaidasAvulsasProdutos')->name('admin.saidas_avulsas.produtos.to_search');
            Route::get('/saidas_avulsas/{saida_avulsa_id}/produtos/create', 'SaidasAvulsasProdutosController@createSaidasAvulsasProdutos')->name('admin.saidas_avulsas.produtos.to_create');
            Route::post('/saidas_avulsas/{saida_avulsa_id}/produtos/store', 'SaidasAvulsasProdutosController@storeSaidasAvulsasProdutos')->name('admin.saidas_avulsas.produtos.to_store');
            Route::post('/saidas_avulsas/{saida_avulsa_id}/produtos/{id}/update', 'SaidasAvulsasProdutosController@updateSaidasAvulsasProdutos')->name('admin.saidas_avulsas.produtos.to_update');
            Route::get('/saidas_avulsas/{saida_avulsa_id}/produtos/{id}/remove', 'SaidasAvulsasProdutosController@removeSaidasAvulsasProdutos')->name('admin.saidas_avulsas.produtos.to_remove');
            Route::get('/saidas_avulsas/{saida_avulsa_id}/receitas/{id}/remove', 'SaidasAvulsasProdutosController@removeSaidasAvulsasReceitas')->name('admin.saidas_avulsas.receitas.to_remove');
    
            // Rotas do controlador de validações de produtos de saídas avulsas
            Route::any('/saidas_avulsas/validacoes', 'SaidasAvulsasValidacoesController@listingSaidasAvulsasValidacoes')->name('admin.saidas_avulsas_validacoes');
            Route::get('/saidas_avulsas/validacoes/{saida_avulsa_id}/create', 'SaidasAvulsasValidacoesController@createSaidasAvulsasValidacoes')->name('admin.saidas_avulsas_validacoes.to_create');
            Route::get('/saidas_avulsas/validacoes/{saida_avulsa_id}/close', 'SaidasAvulsasValidacoesController@closeSaidasAvulsasValidacoes')->name('admin.saidas_avulsas_validacoes.to_close');
            Route::post('/saidas_avulsas/validacoes/{saida_avulsa_id}/reverse', 'SaidasAvulsasValidacoesController@reverseSaidasAvulsasValidacoes')->name('admin.saidas_avulsas_validacoes.to_reverse');
    
            // Rotas do controlador de baixas justificadas
            Route::get('/baixas_justificadas', 'BaixasJustificadasController@listingBaixasJustificadas')->name('admin.baixas_justificadas');
            Route::any('/baixas_justificadas/search', 'BaixasJustificadasController@searchBaixasJustificadas')->name('admin.baixas_justificadas.to_search');
            Route::get('/baixas_justificadas/create', 'BaixasJustificadasController@createBaixasJustificadas')->name('admin.baixas_justificadas.to_create');
            Route::post('/baixas_justificadas/store', 'BaixasJustificadasController@storeBaixasJustificadas')->name('admin.baixas_justificadas.to_store');
            Route::get('/baixas_justificadas/{id}/edit', 'BaixasJustificadasController@editBaixasJustificadas')->name('admin.baixas_justificadas.to_edit');
            Route::post('/baixas_justificadas/{id}/update', 'BaixasJustificadasController@updateBaixasJustificadas')->name('admin.baixas_justificadas.to_update');
            Route::get('/baixas_justificadas/{id}/remove', 'BaixasJustificadasController@removeBaixasJustificadas')->name('admin.baixas_justificadas.to_remove');
            Route::get('/baixas_justificadas/{id}/view', 'BaixasJustificadasController@viewBaixasJustificadas')->name('admin.baixas_justificadas.to_view');
    
            // Rotas do controlador dos produtos de baixas justificadas
            Route::get('/baixas_justificadas/{baixa_justificada_id}/produtos', 'BaixasJustificadasProdutosController@listingBaixasJustificadasProdutos')->name('admin.baixas_justificadas.produtos');
            Route::any('/baixas_justificadas/{baixa_justificada_id}/produtos/search', 'BaixasJustificadasProdutosController@searchBaixasJustificadasProdutos')->name('admin.baixas_justificadas.produtos.to_search');
            Route::get('/baixas_justificadas/{baixa_justificada_id}/produtos/create', 'BaixasJustificadasProdutosController@createBaixasJustificadasProdutos')->name('admin.baixas_justificadas.produtos.to_create');
            Route::post('/baixas_justificadas/{baixa_justificada_id}/produtos/store', 'BaixasJustificadasProdutosController@storeBaixasJustificadasProdutos')->name('admin.baixas_justificadas.produtos.to_store');
            Route::post('/baixas_justificadas/{baixa_justificada_id}/produtos/{id}/update', 'BaixasJustificadasProdutosController@updateBaixasJustificadasProdutos')->name('admin.baixas_justificadas.produtos.to_update');
            Route::get('/baixas_justificadas/{baixa_justificada_id}/produtos/{id}/remove', 'BaixasJustificadasProdutosController@removeBaixasJustificadasProdutos')->name('admin.baixas_justificadas.produtos.to_remove');
    
            // Rotas do controlador de validações de produtos de saídas avulsas
            Route::any('/baixas_justificadas/validacoes', 'BaixasJustificadasValidacoesController@listingBaixasJustificadasValidacoes')->name('admin.baixas_justificadas_validacoes');
            Route::get('/baixas_justificadas/validacoes/{baixa_justificada_id}/create', 'BaixasJustificadasValidacoesController@createBaixasJustificadasValidacoes')->name('admin.baixas_justificadas_validacoes.to_create');
            Route::get('/baixas_justificadas/validacoes/{baixa_justificada_id}/close', 'BaixasJustificadasValidacoesController@closeBaixasJustificadasValidacoes')->name('admin.baixas_justificadas_validacoes.to_close');
            Route::post('/baixas_justificadas/validacoes/{baixa_justificada_id}/reverse', 'BaixasJustificadasValidacoesController@reverseBaixasJustificadasValidacoes')->name('admin.baixas_justificadas_validacoes.to_reverse');

            // Rotas do controlador de lotes de pós-larvas
            Route::get('/lotes', 'LotesController@listingLotes')->name('admin.lotes');
            Route::any('/lotes/search', 'LotesController@searchLotes')->name('admin.lotes.to_search');
            Route::get('/lotes/create', 'LotesController@createLotes')->name('admin.lotes.to_create');
            Route::post('/lotes/store', 'LotesController@storeLotes')->name('admin.lotes.to_store');

            Route::post('/lotes/{lote_id}/larvicultura/store', 'LotesController@storeLotesLarvicultura')->name('admin.lotes.lotes_larvicultura.to_store');// Inserção de Lotes da Larvicultura 
            Route::get('/lotes/{lote_id}/larvicultura/{id}/remove', 'LotesController@removeLotesLarvicultura')->name('admin.lotes.lotes_larvicultura.to_remove');// Exclusão de Lotes da Larvicultura 

            Route::get('/lotes/{id}/edit', 'LotesController@editLotes')->name('admin.lotes.to_edit');
            Route::post('/lotes/{id}/update', 'LotesController@updateLotes')->name('admin.lotes.to_update');
            Route::get('/lotes/{id}/remove', 'LotesController@removeLotes')->name('admin.lotes.to_remove');
            Route::get('/lotes/create/redirectTo/povoamentos/create', 'LotesController@redirectToCreatePovoamentos')->name('admin.lotes.redirect_to.povoamentos.to_create');
            Route::get('/lotes/create/redirectTo/notas_fiscais_entradas/create', 'LotesController@redirectToCreateNotasFiscaisEntradas')->name('admin.lotes.redirect_to.notas_fiscais_entradas.to_create');
            
            // Rotas do controlador de biometrias de lotes
            Route::get('/lotes/{lote_id}/biometrias/create', 'LotesBiometriasController@createLotesBiometrias')->name('admin.lotes.biometrias.to_create');
            Route::post('/lotes/{lote_id}/biometrias/store', 'LotesBiometriasController@storeLotesBiometrias')->name('admin.lotes.biometrias.to_store');
            Route::get('/lotes/{lote_id}/biometrias/{id}/edit', 'LotesBiometriasController@editLotesBiometrias')->name('admin.lotes.biometrias.to_edit');
            Route::post('/lotes/{lote_id}/biometrias/{id}/update', 'LotesBiometriasController@updateLotesBiometrias')->name('admin.lotes.biometrias.to_update');
            Route::get('/lotes/{lote_id}/biometrias/{id}/remove', 'LotesBiometriasController@removeLotesBiometrias')->name('admin.lotes.biometrias.to_remove');
    
            // Rotas do controlador de povoamentosbu
            Route::get('/povoamentos', 'PovoamentosController@listingPovoamentos')->name('admin.povoamentos');
            Route::any('/povoamentos/search', 'PovoamentosController@searchPovoamentos')->name('admin.povoamentos.to_search');
            Route::get('/povoamentos/create', 'PovoamentosController@createPovoamentos')->name('admin.povoamentos.to_create');
            Route::post('/povoamentos/store', 'PovoamentosController@storePovoamentos')->name('admin.povoamentos.to_store');
            Route::get('/povoamentos/{id}/remove', 'PovoamentosController@removePovoamentos')->name('admin.povoamentos.to_remove');
            Route::post('/povoamentos/{id}/close', 'PovoamentosController@closePovoamentos')->name('admin.povoamentos.to_close');
            Route::get('/povoamentos/create/redirectTo/lotes/create', 'PovoamentosController@redirectToCreateLotes')->name('admin.povoamentos.redirect_to.lotes.to_create');
    
            Route::get('/povoamentos/{povoamento_id}/lotes/edit', 'PovoamentosController@editPovoamentosLotes')->name('admin.povoamentos.lotes.to_edit');
            Route::post('/povoamentos/{povoamento_id}/lotes/update', 'PovoamentosController@updatePovoamentosLotes')->name('admin.povoamentos.lotes.to_update');
            Route::get('/povoamentos/{povoamento_id}/lotes/{id}/remove', 'PovoamentosController@removePovoamentosLotes')->name('admin.povoamentos.lotes.to_remove');
    
            // Rotas do controlador de despescas
            Route::get('/despescas', 'DespescasController@listingDespescas')->name('admin.despescas');
            Route::any('/despescas/search', 'DespescasController@searchDespescas')->name('admin.despescas.to_search');
            Route::get('/despescas/create', 'DespescasController@createDespescas')->name('admin.despescas.to_create');
            Route::post('/despescas/store', 'DespescasController@storeDespescas')->name('admin.despescas.to_store');
            Route::get('/despescas/{id}/remove', 'DespescasController@removeDespescas')->name('admin.despescas.to_remove');
            Route::get('/despescas/{id}/close', 'DespescasController@closeDespescas')->name('admin.despescas.to_close');
            Route::get('/despescas/{id}/view', 'DespescasController@viewDespescas')->name('admin.despescas.to_view');
    
            // Rotas do controlador de análises biométricas
            Route::get('/analises_biometricas_old', 'AnalisesBiometricasOldController@listingAnalisesBiometricas')->name('admin.analises_biometricas_old');
            Route::any('/analises_biometricas_old/search', 'AnalisesBiometricasOldController@searchAnalisesBiometricas')->name('admin.analises_biometricas_old.to_search');
            Route::get('/analises_biometricas_old/create', 'AnalisesBiometricasOldController@createAnalisesBiometricas')->name('admin.analises_biometricas_old.to_create');
            Route::post('/analises_biometricas_old/store', 'AnalisesBiometricasOldController@storeAnalisesBiometricas')->name('admin.analises_biometricas_old.to_store');
            Route::get('/analises_biometricas_old/{id}/edit', 'AnalisesBiometricasOldController@editAnalisesBiometricas')->name('admin.analises_biometricas_old.to_edit');
            Route::post('/analises_biometricas_old/{id}/update', 'AnalisesBiometricasOldController@updateAnalisesBiometricas')->name('admin.analises_biometricas_old.to_update');
            Route::get('/analises_biometricas_old/{id}/remove', 'AnalisesBiometricasOldController@removeAnalisesBiometricas')->name('admin.analises_biometricas_old.to_remove');
            Route::get('/analises_biometricas_old/{id}/view', 'AnalisesBiometricasOldController@viewAnalisesBiometricas')->name('admin.analises_biometricas_old.to_view');
    
            // Rotas do controlador de análises biométricas
            Route::get('/analises_biometricas', 'AnalisesBiometricasController@listingAnalisesBiometricas')->name('admin.analises_biometricas');
            Route::any('/analises_biometricas/search', 'AnalisesBiometricasController@searchAnalisesBiometricas')->name('admin.analises_biometricas.to_search');
            Route::get('/analises_biometricas/create', 'AnalisesBiometricasController@createAnalisesBiometricas')->name('admin.analises_biometricas.to_create');
            Route::post('/analises_biometricas/store', 'AnalisesBiometricasController@storeAnalisesBiometricas')->name('admin.analises_biometricas.to_store');
            Route::get('/analises_biometricas/{id}/edit', 'AnalisesBiometricasController@editAnalisesBiometricas')->name('admin.analises_biometricas.to_edit');
            Route::post('/analises_biometricas/{id}/update', 'AnalisesBiometricasController@updateAnalisesBiometricas')->name('admin.analises_biometricas.to_update');
            Route::get('/analises_biometricas/{id}/remove', 'AnalisesBiometricasController@removeAnalisesBiometricas')->name('admin.analises_biometricas.to_remove');
            Route::get('/analises_biometricas/{id}/close', 'AnalisesBiometricasController@closeAnalisesBiometricas')->name('admin.analises_biometricas.to_close');
            Route::get('/analises_biometricas/{id}/view', 'AnalisesBiometricasController@viewAnalisesBiometricas')->name('admin.analises_biometricas.to_view');


            //Rotas do controlador da análises bioensaios(Admin).
            Route::get('analises_bioensaios', 'AnalisesBioensaiosController@listingAnalisesBioensaios')->name('admin.analises_bioensaios');
            Route::get('analises_bioensaios', 'AnalisesBioensaiosController@searchAnalisesBioensaios')->name('admin.analises_bioensaios.to_search');
            Route::get('analises_bioensaios', 'AnalisesBioensaiosController@editAnalisesBioensaios')->name('admin.analises_bioensaios.to_create');
            Route::get('analises_bioensaios/{id}/edit', 'AnalisesBioensaiosController@editAnalisesBioensaios')->name('admin.analises_bioensaios.to_edit');
            Route::get('analises_bioensaios/{id}/update', 'AnalisesBioensaiosController@updateAnalisesBioensaios')->name('admin.analises_bioensaios.to_update');
            Route::get('analises_bioensaios/{id}/remove', 'AnalisesBioensaiosController@removeAnalisesBioensaios')->name('admin.analises_bioensaios.to_remove');
            Route::get('analises_bioensaios/{id}/close', 'AnalisesBioensaiosController@closeAnalisesBioensaios')->name('admin.analises_bioensaios.to_close');


            //Rotas do controlador de coleta de amostras de análises Biometricas
            Route::get('analises_biometricas/amostras', 'AnalisesBiometricasController@storeAnalisesBiometricasAmostras')->name('admin.analises_biometricas.amostras.to_store');
            Route::get('/analises_biometricas/{analise_biometrica_id}/amostras', 'AnalisesBiometricasController@createAnalisesBiometricasAmostras')->name('admin.analises_biometricas.amostras.to_create');
            Route::get('/analises_biometricas/{analise_biometrica_id}/amostras/remove', 'AnalisesBiometricasController@removeAnalisesBiometricasAmostras')->name('admin.analises_biometricas.amostras.to_remove');
            Route::post('/analises_biometricas/{analise_biometrica_id}/amostras/store', 'AnalisesBiometricasController@storeAnalisesBiometricasAmostras')->name('admin.analises_biometricas.amostras.to_store');
            
            //Rotas do controlador que gera as planilhas de biometria
            Route::get('/analises_biometricas/amostras/generate', 'BiometriaParcialController@createBiometriaParcial')->name('admin.analises_biometricas.amostras.to_generate');
            Route::get('/analises_biometricas/amostras/BioParcial/generate', 'BiometriaParcialController@createBiometriaParcial')->name('admin.analises_biometricas.amostras.bioparcial.to_generate');
    

            //Route::get('/analises_bioensaios', [\App\Http\Controllers\Admin\AnalisesBioensaiosController::class, 'analises_bioensaios'])->name('admin.analises_bioensaios');
            //Rotas do controlador de Análises Bioensaios
            //Route::get('/analises_bioensaios', [\App\Http\Controllers\Admin\AnalisesBioensaiosController::class, 'analises_bioensaios'])->name('admin.analises_bioensaios');

            // Rotas do controlador de certificacao de Reprodutores
            Route::get('/certificacao_reprodutores', 'CertificacaoReprodutoresController@listingCertificacaoReprodutores')->name('admin.certificacao_reprodutores');
            Route::any('/certificacao_reprodutores/search', 'CertificacaoReprodutoresController@searchCertificacaoReprodutores')->name('admin.certificacao_reprodutores.to_search');
            Route::get('/certificacao_reprodutores/create', 'CertificacaoReprodutoresController@createCertificacaoReprodutores')->name('admin.certificacao_reprodutores.to_create');
            Route::post('/certificacao_reprodutores/store', 'CertificacaoReprodutoresController@storeCertificacaoReprodutores')->name('admin.certificacao_reprodutores.to_store');
            Route::get('/certificacao_reprodutores/{id}/edit', 'CertificacaoReprodutoresController@editCertificacaoReprodutores')->name('admin.certificacao_reprodutores.to_edit');
            Route::post('/certificacao_reprodutores/{id}/update', 'CertificacaoReprodutoresController@updateCertificacaoReprodutores')->name('admin.certificacao_reprodutores.to_update');
            Route::get('/certificacao_reprodutores/{id}/remove', 'CertificacaoReprodutoresController@removeCertificacaoReprodutores')->name('admin.certificacao_reprodutores.to_remove');
            Route::get('/certificacao_reprodutores/{id}/close', 'CertificacaoReprodutoresController@closeCertificacaoReprodutores')->name('admin.certificacao_reprodutores.to_close');
            Route::get('/certificacao_reprodutores/{id}/view', 'CertificacaoReprodutoresController@viewCertificacaoReprodutores')->name('admin.certificacao_reprodutores.to_view');
            Route::post('/certificacao_reprodutores/{id}/import', 'CertificacaoReprodutoresController@importCertificacaoReprodutores')->name('admin.certificacao_reprodutores.to_import');

            // Rotas do controlador de reprodutores
            Route::get('/certificacao_reprodutores/reprodutores', 'CertificacaoReprodutoresController@listingReprodutores')->name('admin.certificacao_reprodutores.reprodutores');
            Route::get('/certificacao_reprodutores/{id}/reprodutores/search', 'CertificacaoReprodutoresController@searchReprodutores')->name('admin.certificacao_reprodutores.reprodutores.to_search');
            Route::get('/certificacao_reprodutores/{id}/reprodutores/create', 'CertificacaoReprodutoresController@createReprodutores')->name('admin.certificacao_reprodutores.reprodutores.to_create');
            Route::post('/certificacao_reprodutores/reprodutores/{id}/update', 'CertificacaoReprodutoresController@updateReprodutores')->name('admin.certificacao_reprodutores.reprodutores.to_update');
            Route::post('/certificacao_reprodutores/{id}/reprodutores/store', 'CertificacaoReprodutoresController@storeReprodutores')->name('admin.certificacao_reprodutores.reprodutores.to_store');
            Route::get('/certificacao_reprodutores/reprodutores/{id}/remove', 'CertificacaoReprodutoresController@removeReprodutores')->name('admin.certificacao_reprodutores.reprodutores.to_remove');

            // Rotas do controlador de análises reprodutores
            Route::get('/reprodutores_analises', 'ReprodutoresAnalisesController@listingReprodutoresAnalises')->name('admin.reprodutores_analises');
            Route::any('/reprodutores_analises/search', 'ReprodutoresAnalisesController@searchReprodutoresAnalises')->name('admin.reprodutores_analises.to_search');
            Route::get('/reprodutores_analises/create', 'ReprodutoresAnalisesController@createReprodutoresAnalises')->name('admin.reprodutores_analises.to_create');
            Route::post('/reprodutores_analises/{id}/update', 'ReprodutoresAnalisesController@updateReprodutoresAnalises')->name('admin.reprodutores_analises.to_update');
            Route::post('/reprodutores_analises/store', 'ReprodutoresAnalisesController@storeReprodutoresAnalises')->name('admin.reprodutores_analises.to_store');            
            Route::get('/reprodutores_analises/{id}/edit', 'ReprodutoresAnalisesController@editReprodutoresAnalises')->name('admin.reprodutores_analises.to_edit');
            Route::get('/reprodutores_analises/{id}/remove', 'ReprodutoresAnalisesController@removeReprodutoresAnalises')->name('admin.reprodutores_analises.to_remove');
            Route::get('/reprodutores_analises/{id}/turn', 'ReprodutoresAnalisesController@turnReprodutoresAnalises')->name('admin.reprodutores_analises.to_turn');
            Route::get('/reprodutores_analises/{id}/alertas/turn', 'ReprodutoresAnalisesController@turnReprodutoresAnalisesAlertas')->name('admin.reprodutores_analises.alertas.to_turn');
            
            // Rotas do controlador de análises bacteriológicas
            Route::get('/analises_bacteriologicas', 'AnalisesBacteriologicasController@listingAnalisesBacteriologicas')->name('admin.analises_bacteriologicas');
            Route::any('/analises_bacteriologicas/search', 'AnalisesBacteriologicasController@searchAnalisesBacteriologicas')->name('admin.analises_bacteriologicas.to_search');
            Route::get('/analises_bacteriologicas/create', 'AnalisesBacteriologicasController@createAnalisesBacteriologicas')->name('admin.analises_bacteriologicas.to_create');
            Route::post('/analises_bacteriologicas/store', 'AnalisesBacteriologicasController@storeAnalisesBacteriologicas')->name('admin.analises_bacteriologicas.to_store');
            Route::get('/analises_bacteriologicas/{id}/edit', 'AnalisesBacteriologicasController@editAnalisesBacteriologicas')->name('admin.analises_bacteriologicas.to_edit');
            Route::post('/analises_bacteriologicas/{id}/update', 'AnalisesBacteriologicasController@updateAnalisesBacteriologicas')->name('admin.analises_bacteriologicas.to_update');
            Route::get('/analises_bacteriologicas/{id}/remove', 'AnalisesBacteriologicasController@removeAnalisesBacteriologicas')->name('admin.analises_bacteriologicas.to_remove');

            Route::get('/analises_bacteriologicas/{analise_laboratorial_id}/amostras/create', 'AnalisesBacteriologicasAmostrasController@create')->name('admin.analises_bacteriologicas.amostras.to_create');
            Route::post('/analises_bacteriologicas/{analise_laboratorial_id}/amostras/store', 'AnalisesBacteriologicasAmostrasController@store')->name('admin.analises_bacteriologicas.amostras.to_store');
            Route::get('/analises_bacteriologicas/{analise_laboratorial_id}/amostras/{id}/remove', 'AnalisesBacteriologicasAmostrasController@remove')->name('admin.analises_bacteriologicas.amostras.to_remove');
    
            // Rotas do controlador de análises cálcicas
            Route::get('/analises_calcicas', 'AnalisesCalcicasController@listingAnalisesCalcicas')->name('admin.analises_calcicas');
            Route::any('/analises_calcicas/search', 'AnalisesCalcicasController@searchAnalisesCalcicas')->name('admin.analises_calcicas.to_search');
            Route::get('/analises_calcicas/create', 'AnalisesCalcicasController@createAnalisesCalcicas')->name('admin.analises_calcicas.to_create');
            Route::post('/analises_calcicas/store', 'AnalisesCalcicasController@storeAnalisesCalcicas')->name('admin.analises_calcicas.to_store');
            Route::get('/analises_calcicas/{id}/edit', 'AnalisesCalcicasController@editAnalisesCalcicas')->name('admin.analises_calcicas.to_edit');
            Route::post('/analises_calcicas/{id}/update', 'AnalisesCalcicasController@updateAnalisesCalcicas')->name('admin.analises_calcicas.to_update');
            Route::get('/analises_calcicas/{id}/remove', 'AnalisesCalcicasController@removeAnalisesCalcicas')->name('admin.analises_calcicas.to_remove');

            Route::get('/analises_calcicas/{analise_laboratorial_id}/amostras/create', 'AnalisesCalcicasAmostrasController@create')->name('admin.analises_calcicas.amostras.to_create');
            Route::post('/analises_calcicas/{analise_laboratorial_id}/amostras/store', 'AnalisesCalcicasAmostrasController@store')->name('admin.analises_calcicas.amostras.to_store');
            Route::get('/analises_calcicas/{analise_laboratorial_id}/amostras/{id}/remove', 'AnalisesCalcicasAmostrasController@remove')->name('admin.analises_calcicas.amostras.to_remove');

            // Rotas do controlador de análises cálcicas
            Route::get('/analises_condicionais', 'AnalisesCondicionaisController@listingAnalisesCondicionais')->name('admin.analises_condicionais');
            Route::any('/analises_condicionais/search', 'AnalisesCondicionaisController@searchAnalisesCondicionais')->name('admin.analises_condicionais.to_search');
            Route::get('/analises_condicionais/create', 'AnalisesCondicionaisController@createAnalisesCondicionais')->name('admin.analises_condicionais.to_create');
            Route::post('/analises_condicionais/store', 'AnalisesCondicionaisController@storeAnalisesCondicionais')->name('admin.analises_condicionais.to_store');
            Route::get('/analises_condicionais/{analise_condicional_id}/edit', 'AnalisesCondicionaisController@editAnalisesCondicionais')->name('admin.analises_condicionais.to_edit');
            Route::post('/analises_condicionais/{analise_condicional_id}/update', 'AnalisesCondicionaisController@updateAnalisesCondicionais')->name('admin.analises_condicionais.to_update');
            Route::get('/analises_condicionais/{analise_condicional_id}/remove', 'AnalisesCondicionaisController@removeAnalisesCondicionais')->name('admin.analises_condicionais.to_remove');

            Route::get('/analises_condicionais/{analise_condicional_id}/presuntiva', 'AnalisesCondicionaisController@listingCondicionalPresuntiva')->name('admin.analises_condicionais.analises_presuntivas');
            Route::get('/analises_condicionais/{analise_condicional_id}/presuntiva/create', 'AnalisesCondicionaisController@createCondicionalPresuntiva')->name('admin.analises_condicionais.analises_presuntivas.to_create');

            Route::get('/analises_condicionais/{analise_condicional_id}/bacteriologia', 'AnalisesCondicionaisController@listingCondicionalBacteriologicas')->name('admin.analises_condicionais.analises_bacteriologicas');
            Route::get('/analises_condicionais/{analise_condicional_id}/bacteriologia/create', 'AnalisesCondicionaisController@createCondicionalBacteriologicas')->name('admin.analises_condicionais.analises_bacteriologicas.to_create');
            
            Route::get('/analises_condicionais/{analise_condicional_id}/parametro', 'AnalisesCondicionaisController@listingCondicionalParametro')->name('admin.analises_condicionais.coletas_parametros');
            Route::get('/analises_condicionais/{analise_condicional_id}/parametro/create', 'AnalisesCondicionaisController@createCondicionalParametro')->name('admin.analises_condicionais.coletas_parametros.to_create');
            

            // Rotas do controlador de programacao de arraçoamentos
            Route::get('/arracoamentos', 'ArracoamentosController@listingArracoamentos')->name('admin.arracoamentos');
            Route::any('/arracoamentos/search', 'ArracoamentosController@searchArracoamentos')->name('admin.arracoamentos.to_search');
            Route::get('/arracoamentos/create', 'ArracoamentosController@createArracoamentos')->name('admin.arracoamentos.to_create');
            Route::post('/arracoamentos/store', 'ArracoamentosController@storeArracoamentos')->name('admin.arracoamentos.to_store');
            Route::post('/arracoamentos/{id}/update', 'ArracoamentosController@updateArracoamentos')->name('admin.arracoamentos.to_update');
            Route::get('/arracoamentos/{id}/remove', 'ArracoamentosController@removeArracoamentos')->name('admin.arracoamentos.to_remove');
    
            // Rotas do controlador de horários de arraçoamentos
            Route::get('/arracoamentos/{arracoamento_id}/arracoamentos_horarios', 'ArracoamentosHorariosController@listingArracoamentosHorarios')->name('admin.arracoamentos.arracoamentos_horarios');
            Route::get('/arracoamentos/{arracoamento_id}/arracoamentos_horarios/store', 'ArracoamentosHorariosController@storeArracoamentosHorarios')->name('admin.arracoamentos.arracoamentos_horarios.to_store');
            Route::post('/arracoamentos/{arracoamento_id}/arracoamentos_horarios/generate', 'ArracoamentosHorariosController@generateArracoamentosHorarios')->name('admin.arracoamentos.arracoamentos_horarios.to_generate');
            Route::get('/arracoamentos/{arracoamento_id}/arracoamentos_horarios/{id}/remove', 'ArracoamentosHorariosController@removeArracoamentosHorarios')->name('admin.arracoamentos.arracoamentos_horarios.to_remove');
    
            // Rotas do controlador de aplicações de arraçoamentos
            Route::post('/arracoamentos/{arracoamento_id}/arracoamentos_horarios/{arracoamento_horario_id}/arracoamentos_aplicacoes/store', 'ArracoamentosAplicacoesController@storeArracoamentosAplicacoes')->name('admin.arracoamentos.arracoamentos_aplicacoes.to_store');
            Route::post('/arracoamentos/{arracoamento_id}/arracoamentos_horarios/{arracoamento_horario_id}/arracoamentos_aplicacoes/{id}/update', 'ArracoamentosAplicacoesController@updateArracoamentosAplicacoes')->name('admin.arracoamentos.arracoamentos_aplicacoes.to_update');
            Route::get('/arracoamentos/{arracoamento_id}/arracoamentos_horarios/{arracoamento_horario_id}/arracoamentos_aplicacoes/{id}/remove', 'ArracoamentosAplicacoesController@removeArracoamentosAplicacoes')->name('admin.arracoamentos.arracoamentos_aplicacoes.to_remove');
            
            // Rotas do controlador do cadastro de períodos climáticos
            Route::get('/arracoamentos_climas', 'ArracoamentosClimasController@listingArracoamentosClimas')->name('admin.arracoamentos_climas');
            Route::any('/arracoamentos_climas/search', 'ArracoamentosClimasController@searchArracoamentosClimas')->name('admin.arracoamentos_climas.to_search');
            Route::get('/arracoamentos_climas/create', 'ArracoamentosClimasController@createArracoamentosClimas')->name('admin.arracoamentos_climas.to_create');
            Route::post('/arracoamentos_climas/store', 'ArracoamentosClimasController@storeArracoamentosClimas')->name('admin.arracoamentos_climas.to_store');
            Route::get('/arracoamentos_climas/{id}/edit', 'ArracoamentosClimasController@editArracoamentosClimas')->name('admin.arracoamentos_climas.to_edit');
            Route::post('/arracoamentos_climas/{id}/update', 'ArracoamentosClimasController@updateArracoamentosClimas')->name('admin.arracoamentos_climas.to_update');
            Route::get('/arracoamentos_climas/{id}/remove', 'ArracoamentosClimasController@removeArracoamentosClimas')->name('admin.arracoamentos_climas.to_remove');
            Route::get('/arracoamentos_climas/{id}/turn', 'ArracoamentosClimasController@turnArracoamentosClimas')->name('admin.arracoamentos_climas.to_turn');
    
            // Rotas do controlador do cadastro de referenciais de arraçoamentos por pesos médios
            Route::get('/arracoamentos_climas/{arracoamento_clima_id}/arracoamentos_referenciais', 'ArracoamentosReferenciaisController@listingArracoamentosReferenciais')->name('admin.arracoamentos_climas.arracoamentos_referenciais');
            Route::any('/arracoamentos_climas/{arracoamento_clima_id}/arracoamentos_referenciais/search', 'ArracoamentosReferenciaisController@searchArracoamentosReferenciais')->name('admin.arracoamentos_climas.arracoamentos_referenciais.to_search');
            Route::get('/arracoamentos_climas/{arracoamento_clima_id}/arracoamentos_referenciais/create', 'ArracoamentosReferenciaisController@createArracoamentosReferenciais')->name('admin.arracoamentos_climas.arracoamentos_referenciais.to_create');
            Route::post('/arracoamentos_climas/{arracoamento_clima_id}/arracoamentos_referenciais/store', 'ArracoamentosReferenciaisController@storeArracoamentosReferenciais')->name('admin.arracoamentos_climas.arracoamentos_referenciais.to_store');
            Route::get('/arracoamentos_climas/{arracoamento_clima_id}/arracoamentos_referenciais/{id}/edit', 'ArracoamentosReferenciaisController@editArracoamentosReferenciais')->name('admin.arracoamentos_climas.arracoamentos_referenciais.to_edit');
            Route::post('/arracoamentos_climas/{arracoamento_clima_id}/arracoamentos_referenciais/{id}/update', 'ArracoamentosReferenciaisController@updateArracoamentosReferenciais')->name('admin.arracoamentos_climas.arracoamentos_referenciais.to_update');
            Route::get('/arracoamentos_climas/{arracoamento_clima_id}/arracoamentos_referenciais/{id}/remove', 'ArracoamentosReferenciaisController@removeArracoamentosReferenciais')->name('admin.arracoamentos_climas.arracoamentos_referenciais.to_remove');
    
            // Rotas do controlador de tipos de aplicações de arraçoamentos
            Route::get('/arracoamentos_aplicacoes_tipos', 'ArracoamentosAplicacoesTiposController@listingArracoamentosAplicacoesTipos')->name('admin.arracoamentos_aplicacoes_tipos');
            Route::any('/arracoamentos_aplicacoes_tipos/search', 'ArracoamentosAplicacoesTiposController@searchArracoamentosAplicacoesTipos')->name('admin.arracoamentos_aplicacoes_tipos.to_search');
            Route::get('/arracoamentos_aplicacoes_tipos/create', 'ArracoamentosAplicacoesTiposController@createArracoamentosAplicacoesTipos')->name('admin.arracoamentos_aplicacoes_tipos.to_create');
            Route::post('/arracoamentos_aplicacoes_tipos/store', 'ArracoamentosAplicacoesTiposController@storeArracoamentosAplicacoesTipos')->name('admin.arracoamentos_aplicacoes_tipos.to_store');
            Route::get('/arracoamentos_aplicacoes_tipos/{id}/edit', 'ArracoamentosAplicacoesTiposController@editArracoamentosAplicacoesTipos')->name('admin.arracoamentos_aplicacoes_tipos.to_edit');
            Route::post('/arracoamentos_aplicacoes_tipos/{id}/update', 'ArracoamentosAplicacoesTiposController@updateArracoamentosAplicacoesTipos')->name('admin.arracoamentos_aplicacoes_tipos.to_update');
            Route::get('/arracoamentos_aplicacoes_tipos/{id}/remove', 'ArracoamentosAplicacoesTiposController@removeArracoamentosAplicacoesTipos')->name('admin.arracoamentos_aplicacoes_tipos.to_remove');
    
            // Rotas do controlador de validações de arraçoamentos
            Route::any('/arracoamentos/validacoes', 'ArracoamentosValidacoesController@listingArracoamentosValidacoes')->name('admin.arracoamentos_validacoes');
            Route::get('/arracoamentos/validacoes/{arracoamento_id}/create', 'ArracoamentosValidacoesController@createArracoamentosValidacoes')->name('admin.arracoamentos_validacoes.to_create');
            Route::get('/arracoamentos/validacoes/{arracoamento_id}/close', 'ArracoamentosValidacoesController@closeArracoamentosValidacoes')->name('admin.arracoamentos_validacoes.to_close');
            Route::post('/arracoamentos/validacoes/{arracoamento_id}/reverse', 'ArracoamentosValidacoesController@reverseArracoamentosValidacoes')->name('admin.arracoamentos_validacoes.to_reverse');
    
            // Rotas do controlador de grupos de aplicações de insumos
            Route::get('/aplicacoes_insumos_grupos', 'AplicacoesInsumosGruposController@listingAplicacoesInsumosGrupos')->name('admin.aplicacoes_insumos_grupos');
            Route::any('/aplicacoes_insumos_grupos/search', 'AplicacoesInsumosGruposController@searchAplicacoesInsumosGrupos')->name('admin.aplicacoes_insumos_grupos.to_search');
            Route::get('/aplicacoes_insumos_grupos/create', 'AplicacoesInsumosGruposController@createAplicacoesInsumosGrupos')->name('admin.aplicacoes_insumos_grupos.to_create');
            Route::post('/aplicacoes_insumos_grupos/store', 'AplicacoesInsumosGruposController@storeAplicacoesInsumosGrupos')->name('admin.aplicacoes_insumos_grupos.to_store');
            Route::get('/aplicacoes_insumos_grupos/{id}/edit', 'AplicacoesInsumosGruposController@editAplicacoesInsumosGrupos')->name('admin.aplicacoes_insumos_grupos.to_edit');
            Route::post('/aplicacoes_insumos_grupos/{id}/update', 'AplicacoesInsumosGruposController@updateAplicacoesInsumosGrupos')->name('admin.aplicacoes_insumos_grupos.to_update');
            Route::get('/aplicacoes_insumos_grupos/{id}/remove', 'AplicacoesInsumosGruposController@removeAplicacoesInsumosGrupos')->name('admin.aplicacoes_insumos_grupos.to_remove');
    
            // Rotas do controlador do registro de aplicações
            Route::any('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/registro_aplicacoes/view', 'AplicacoesInsumosController@viewAplicacoesInsumos')->name('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_view');
            Route::post('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/registro_aplicacoes/store', 'AplicacoesInsumosController@storeAplicacoesInsumos')->name('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_store');
            Route::get('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/registro_aplicacoes/{id}/remove', 'AplicacoesInsumosController@removeAplicacoesInsumos')->name('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_remove');
            Route::post('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/registro_aplicacoes/{id}/update', 'AplicacoesInsumosController@updateAplicacoesInsumos')->name('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_update');
            
            Route::post('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/observacoes/store', 'AplicacoesInsumosController@storeAplicacoesInsumosGruposObservacoes')->name('admin.aplicacoes_insumos_grupos.observacoes.to_store');
    
            // Rotas do controlador dos itens das aplicações de insumos
            // Route::post('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/registro_aplicacoes/{aplicacao_insumo_id}/receitas/{id}/update', 'AplicacoesInsumosReceitasController@updateAplicacoesInsumosReceitas')->name('admin.aplicacoes_insumos_grupos.registro_aplicacoes.receitas.to_update');
            Route::get('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/registro_aplicacoes/receitas/{id}/remove', 'AplicacoesInsumosReceitasController@removeAplicacoesInsumosReceitas')->name('admin.aplicacoes_insumos_grupos.registro_aplicacoes.receitas.to_remove');
            // Route::post('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/registro_aplicacoes/{aplicacao_insumo_id}/produtos/{id}/update', 'AplicacoesInsumosProdutosController@updateAplicacoesInsumosProdutos')->name('admin.aplicacoes_insumos_grupos.registro_aplicacoes.produtos.to_update');
            Route::get('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/registro_aplicacoes/produtos/{id}/remove', 'AplicacoesInsumosProdutosController@removeAplicacoesInsumosProdutos')->name('admin.aplicacoes_insumos_grupos.registro_aplicacoes.produtos.to_remove');
    
            // Rotas do controlador de ajustes de aplicações de insumos
            Route::any('/aplicacoes_insumos/ajustes/create', 'AplicacoesInsumosAjustesController@createAplicacoesInsumosAjustes')->name('admin.aplicacoes_insumos_ajustes');
            Route::any('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/registro_aplicacoes/ajustes/create', 'AplicacoesInsumosAjustesController@createAplicacoesInsumosAjustes')->name('admin.aplicacoes_insumos_grupos.registro_aplicacoes.ajustes.to_create');
            Route::any('/aplicacoes_insumos_grupos/{aplicacao_insumo_grupo_id}/registro_aplicacoes/ajustes/store', 'AplicacoesInsumosAjustesController@storeAplicacoesInsumosAjustes')->name('admin.aplicacoes_insumos_grupos.registro_aplicacoes.ajustes.to_store');
    
            // Rotas do controlador de validações das aplicações de insumos
            Route::any('/aplicacoes_insumos/validacoes', 'AplicacoesInsumosValidacoesController@listingAplicacoesInsumosValidacoes')->name('admin.aplicacoes_insumos_validacoes');
            Route::get('/aplicacoes_insumos/validacoes/{aplicacao_insumo_id}/close', 'AplicacoesInsumosValidacoesController@closeAplicacoesInsumosValidacoes')->name('admin.aplicacoes_insumos_validacoes.to_close');
            Route::post('/aplicacoes_insumos/validacoes/{aplicacao_insumo_id}/reverse', 'AplicacoesInsumosValidacoesController@reverseAplicacoesInsumosValidacoes')->name('admin.aplicacoes_insumos_validacoes.to_reverse');
    
            // Rotas do controlador de grupos de rateios de insumos
            Route::get('/grupos_rateios', 'GruposRateiosController@listingGruposRateios')->name('admin.grupos_rateios');
            Route::any('/grupos_rateios/search', 'GruposRateiosController@searchGruposRateios')->name('admin.grupos_rateios.to_search');
            Route::get('/grupos_rateios/create', 'GruposRateiosController@createGruposRateios')->name('admin.grupos_rateios.to_create');
            Route::post('/grupos_rateios/store', 'GruposRateiosController@storeGruposRateios')->name('admin.grupos_rateios.to_store');
            Route::get('/grupos_rateios/{id}/edit', 'GruposRateiosController@editGruposRateios')->name('admin.grupos_rateios.to_edit');
            Route::post('/grupos_rateios/{id}/update', 'GruposRateiosController@updateGruposRateios')->name('admin.grupos_rateios.to_update');
            Route::get('/grupos_rateios/{id}/remove', 'GruposRateiosController@removeGruposRateios')->name('admin.grupos_rateios.to_remove');
    
            // Rotas do controlador de cadastro de tanques para grupos de rateios
            Route::get('/grupos_rateios/{grupo_rateio_id}/tanques', 'GruposRateiosTanquesController@listingGruposRateiosTanques')->name('admin.grupos_rateios.grupos_tanques');
            Route::any('/grupos_rateios/{grupo_rateio_id}/tanques/search', 'GruposRateiosTanquesController@searchGruposRateiosTanques')->name('admin.grupos_rateios.grupos_tanques.to_search');
            Route::post('/grupos_rateios/{grupo_rateio_id}/tanques/store', 'GruposRateiosTanquesController@storeGruposRateiosTanques')->name('admin.grupos_rateios.grupos_tanques.to_store');
            Route::post('/grupos_rateios/{grupo_rateio_id}/tanques/{id}/update', 'GruposRateiosTanquesController@updateGruposRateiosTanques')->name('admin.grupos_rateios.grupos_tanques.to_update');
            Route::get('/grupos_rateios/{grupo_rateio_id}/tanques/{id}/remove', 'GruposRateiosTanquesController@removeGruposRateiosTanques')->name('admin.grupos_rateios.grupos_tanques.to_remove');
            Route::get('/grupos_rateios/{grupo_rateio_id}/tanques/{id}/turn', 'GruposRateiosTanquesController@turnGruposRateiosTanques')->name('admin.grupos_rateios.grupos_tanques.to_turn');
    
            // Rotas do controlador do histórico de exportações
            Route::get('/historico_exportacoes', 'HistoricoExportacoesController@listingHistoricoExportacoes')->name('admin.historico_exportacoes');
            Route::any('/historico_exportacoes/search', 'HistoricoExportacoesController@searchHistoricoExportacoes')->name('admin.historico_exportacoes.to_search');
            Route::get('/historico_exportacoes/{id}/remove', 'HistoricoExportacoesController@removeHistoricoExportacoes')->name('admin.historico_exportacoes.to_remove');
            Route::post('/historico_exportacoes/export', 'HistoricoExportacoesController@exportHistoricoExportacoes')->name('admin.historico_exportacoes.to_export');

            // Rotas do controlador do histórico de importacoes
            Route::get('/historico_importacoes', 'HistoricoImportacoesController@listingHistoricoImportacoes')->name('admin.historico_importacoes');
            Route::any('/historico_importacoes/search', 'HistoricoImportacoesController@searchHistoricoImportacoes')->name('admin.historico_importacoes.to_search');
            Route::get('/historico_importacoes/{id}/remove', 'HistoricoImportacoesController@removeHistoricoImportacoes')->name('admin.historico_importacoes.to_remove');
            Route::post('/historico_importacoes/import', 'HistoricoImportacoesController@importHistoricoImportacoes')->name('admin.historico_importacoes.to_import');

            // Rotas do controlador de lotes de peixes
            Route::get('/lotes_peixes', 'LotesPeixesController@listing')->name('admin.lotes_peixes');
            Route::any('/lotes_peixes/search', 'LotesPeixesController@search')->name('admin.lotes_peixes.to_search');
            Route::get('/lotes_peixes/create', 'LotesPeixesController@create')->name('admin.lotes_peixes.to_create');
            Route::post('/lotes_peixes/store', 'LotesPeixesController@store')->name('admin.lotes_peixes.to_store');
            Route::get('/lotes_peixes/{id}/remove', 'LotesPeixesController@remove')->name('admin.lotes_peixes.to_remove');

            // Rotas do controlador de registro de ovos coletados
            Route::get('/lotes_peixes/{lote_peixes_id}/ovos', 'LotesPeixesOvosController@create')->name('admin.lotes_peixes.ovos.to_create');
            Route::post('/lotes_peixes/{lote_peixes_id}/ovos/store', 'LotesPeixesOvosController@store')->name('admin.lotes_peixes.ovos.to_store');
            Route::get('/lotes_peixes/{lote_peixes_id}/ovos/{id}/remove', 'LotesPeixesOvosController@remove')->name('admin.lotes_peixes.ovos.to_remove');

            // Rotas do controlador de transferências de peixes
            Route::get('/transferencias_peixes', 'TransferenciasPeixesController@listing')->name('admin.transferencias_peixes');
            Route::any('/transferencias_peixes/search', 'TransferenciasPeixesController@search')->name('admin.transferencias_peixes.to_search');
            Route::get('/transferencias_peixes/create', 'TransferenciasPeixesController@create')->name('admin.transferencias_peixes.to_create');
            Route::post('/transferencias_peixes/store', 'TransferenciasPeixesController@store')->name('admin.transferencias_peixes.to_store');
            Route::get('/transferencias_peixes/{id}/remove', 'TransferenciasPeixesController@remove')->name('admin.transferencias_peixes.to_remove');

            // Rotas do controlador de recriação de peixes
            Route::get('/recriacao_peixes', 'RecriacaoPeixesController@listing')->name('admin.recriacao_peixes');
            Route::any('/recriacao_peixes/search', 'RecriacaoPeixesController@search')->name('admin.recriacao_peixes.to_search');
            Route::get('/recriacao_peixes/create', 'RecriacaoPeixesController@create')->name('admin.recriacao_peixes.to_create');
            Route::post('/recriacao_peixes/store', 'RecriacaoPeixesController@store')->name('admin.recriacao_peixes.to_store');
            Route::get('/recriacao_peixes/{id}/remove', 'RecriacaoPeixesController@remove')->name('admin.recriacao_peixes.to_remove');

            // Rotas do controlador de registro de ovos coletados
            Route::get('/recriacao_peixes/{recriacao_peixes_id}/lotes', 'RecriacaoPeixesLotesController@create')->name('admin.recriacao_peixes.lotes.to_create');
            Route::post('/recriacao_peixes/{recriacao_peixes_id}/lotes/store', 'RecriacaoPeixesLotesController@store')->name('admin.recriacao_peixes.lotes.to_store');
            Route::get('/recriacao_peixes/{recriacao_peixes_id}/lotes/{id}/remove', 'RecriacaoPeixesLotesController@remove')->name('admin.recriacao_peixes.lotes.to_remove');

        });

        /*
        |--------------------------------------------------------------------------
        | Rotas de relatórios
        |--------------------------------------------------------------------------
        */
        Route::namespace('Reports')->group(function() {

            // Relatório de resumo de análises presuntivas
            Route::get('/relatorios/resumo_analises_presuntivas', 'ResumoAnalisesPresuntivasController@createResumoAnalisesPresuntivas')->name('admin.resumo_analises_presuntivas');
            Route::post('/relatorios/resumo_analises_presuntivas/generate', 'ResumoAnalisesPresuntivasController@generateResumoAnalisesPresuntivas')->name('admin.resumo_analises_presuntivas.to_view');

            // Relatório de resumo de análises de água
            Route::get('/relatorios/resumo_analises_agua', 'ResumoAnalisesAguaController@createResumoAnalisesAgua')->name('admin.resumo_analises_agua');
            Route::post('/relatorios/resumo_analises_agua/generate', 'ResumoAnalisesAguaController@generateResumoAnalisesAgua')->name('admin.resumo_analises_agua.to_view');

            // Relatório de resumo de análises de bacteriologia
            Route::get('/relatorios/resumo_analises_bacteriologica', 'ResumoAnalisesBacteriologicasController@createResumoAnalisesBacteriologicas')->name('admin.resumo_analises_bacteriologicas');
            Route::post('/relatorios/resumo_analises_bacteriologica/generate', 'ResumoAnalisesBacteriologicasController@generateResumoAnalisesBacteriologicas')->name('admin.resumo_analises_bacteriologicas.to_view');

            // Relatório de resumo de análises biométricas
            Route::get('/relatorios/resumo_analises_biometricas', 'ResumoAnalisesBiometricasController@createBiometria')->name('admin.resumo_analises_biometricas');
            Route::post('/relatorios/resumo_analises_biometricas/generate', 'ResumoAnalisesBiometricasController@generateBiometria')->name('admin.resumo_analises_biometricas.to_view');

            // Novo Relatório(teste) de resumo de análises biométricas
            Route::get('/relatorios/analise_biometricas', 'RelAnaliseBiometricaController@generatePDF')->name('relatorio.pdf');
            
            // Fichas de arracoamento
            Route::post('/relatorios/arracoamentos/fichas/generate', 'ArracoamentosFichasController@generateArracoamentosFichas')->name('admin.arracoamentos.fichas.to_view');

            // Relatório de ativação de probióticos e aditivos
            Route::get('/relatorios/ativacao_probioticos_aditivos', 'AtivacaoProbioticosAditivosController@createAtivacaoProbioticosAditivos')->name('admin.ativacao_probioticos_aditivos');
            Route::post('/relatorios/ativacao_probioticos_aditivos/generate', 'AtivacaoProbioticosAditivosController@generateAtivacaoProbioticosAditivos')->name('admin.ativacao_probioticos_aditivos.to_view');

            // Ficha de aplicação de insumos
            Route::post('/relatorios/aplicacoes_insumos_grupos/fichas/generate', 'AplicacoesInsumosFichasController@generateAplicacoesInsumosFichas')->name('admin.aplicacoes_insumos_grupos.fichas.to_view');

            // Relatório de ativação de insumos para manejos
            Route::get('/relatorios/ativacao_insumos_manejos', 'AtivacaoInsumosManejosController@createAtivacaoInsumosManejos')->name('admin.ativacao_insumos_manejos');
            Route::post('/relatorios/ativacao_insumos_manejos/generate', 'AtivacaoInsumosManejosController@generateAtivacaoInsumosManejos')->name('admin.ativacao_insumos_manejos.to_view');

            // Relatório de consumos por ciclo
            Route::get('/relatorios/consumos_por_ciclo', 'ConsumosPorCicloController@createConsumosPorCiclo')->name('admin.consumos_por_ciclo');
            Route::post('/relatorios/consumos_por_ciclo/generate', 'ConsumosPorCicloController@generateConsumosPorCiclo')->name('admin.consumos_por_ciclo.to_view');

            // Relatório de consumos por produto
            Route::get('/relatorios/envio_para_temporarios', 'EnvioParaTemporariosController@create')->name('admin.envio_para_temporarios');
            Route::post('/relatorios/envio_para_temporarios/generate', 'EnvioParaTemporariosController@generate')->name('admin.envio_para_temporarios.to_view');

            // Relatório de consumos por produto
            Route::get('/relatorios/consumos_por_produto', 'ConsumosPorProdutoController@createConsumosPorProduto')->name('admin.consumos_por_produto');
            Route::post('/relatorios/consumos_por_produto/generate', 'ConsumosPorProdutoController@generateConsumosPorProduto')->name('admin.consumos_por_produto.to_view');

            // Relatório de custos por ciclo
            Route::get('/relatorios/custos_por_ciclo', 'CustosPorCicloController@createCustosPorCiclo')->name('admin.custos_por_ciclo');
            Route::post('/relatorios/custos_por_ciclo/generate', 'CustosPorCicloController@generateCustosPorCiclo')->name('admin.custos_por_ciclo.to_view');

            // Tabela de Mortalidade
            Route::get('/relatorios/mortalidade', 'MortalidadeController@createMortalidade')->name('admin.mortalidade');
            Route::post('/relatorios/mortalidade/generate', 'MortalidadeController@generateMortalidade')->name('admin.mortalidade.to_view');

            // Relatório de resumo de saídas de produtos
            Route::get('/relatorios/resumo_saidas_produtos', 'ResumoSaidasProdutosController@createResumoSaidasProdutos')->name('admin.resumo_saidas_produtos');
            Route::post('/relatorios/resumo_saidas_produtos/generate', 'ResumoSaidasProdutosController@generateResumoSaidasProdutos')->name('admin.resumo_saidas_produtos.to_view');

            // Relatório de permissões de acessos
            Route::get('/relatorios/permissoes_acesso', 'PermissoesAcessoController@createPermissoesAcesso')->name('admin.permissoes_acesso');
            Route::post('/relatorios/permissoes_acesso/generate', 'PermissoesAcessoController@generatePermissoesAcesso')->name('admin.permissoes_acesso.to_view');
            
        });

        /*
        |--------------------------------------------------------------------------
        | Rotas de gráficos
        |--------------------------------------------------------------------------
        */
        Route::namespace('Charts')->group(function() {


          
            // Gráfico de crescimento
            Route::get('/graficos/crescimento', 'CrescimentoChartController@createCrescimentoChart')->name('admin.graficos_crescimento');
            Route::get('/graficos/crescimento/{listagem}', 'CrescimentoChartController@createCrescimentoChart')->name('admin.graficos_crescimento.listagem');
            Route::post('/graficos/crescimento/generate', 'CrescimentoChartController@generateCrescimentoChart')->name('admin.graficos_crescimento.to_generate');
            

            //Gráfico de crescimento das Amostras.
            Route::get('/graficos/amostras', 'AmostrasCrescimentoChartController@createAmostrasCrescimentoChart')->name('admin.graficos_amostras');
            Route::get('graficos/amostras/{listagem}',  'AmostrasCrescimentoChartController@createAmostrasCrescimento')->name('admin.graficos_amostras.listagem');
            Route::post('graficos/amostras/generate', 'AmostrasCrescimentoChartController@generateAmostrasCrescimentoChart')->name('admin.graficos_amostras.to_generate');

            // Gráfico de parâmetros
            Route::get('/graficos/coletas_parametros', 'ColetasParametrosChartController@createColetasParametrosChart')->name('admin.graficos_coletas_parametros');
            Route::get('/graficos/coletas_parametros/{listagem}', 'ColetasParametrosChartController@createColetasParametrosChart')->name('admin.graficos_coletas_parametros.listagem');
            Route::post('/graficos/coletas_parametros/generate', 'ColetasParametrosChartController@generateColetasParametrosChart')->name('admin.graficos_coletas_parametros.to_generate');

             // Gráfico de parâmetros
             Route::get('/graficos/calcio', 'CalcioChartController@createCalcioChart')->name('admin.graficos_calcio');
             Route::get('/graficos/calcio/{listagem}', 'CalcioChartController@createCalcioChart')->name('admin.graficos_calcio.listagem');
             Route::post('/graficos/calcio/generate', 'CalcioChartController@generateCalcioChart')->name('admin.graficos_calcio.to_generate');

            // Gráfico de consumo de ração 
            Route::get('/graficos/consumo_racao', 'ConsumoRacaoChartController@createConsumoRacaoChart')->name('admin.graficos_consumo_racao');
            Route::get('/graficos/consumo_racao/{listagem}', 'ConsumoRacaoChartController@createConsumoRacaoChart')->name('admin.graficos_consumo_racao.listagem');
            Route::post('/graficos/consumo_racao/generate', 'ConsumoRacaoChartController@generateConsumoRacaoChart')->name('admin.graficos_consumo_racao.to_generate');

            // Gráfico de mortalidade 
            Route::get('/graficos/mortalidade', 'MortalidadeChartController@createMortalidadeChart')->name('admin.graficos_mortalidade');
            Route::get('/graficos/mortalidade/{listagem}', 'MortalidadeChartController@createMortalidadeChart')->name('admin.graficos_mortalidade.listagem');
            Route::post('/graficos/mortalidade/generate', 'MortalidadeChartController@generateMortalidadeChart')->name('admin.graficos_mortalidade.to_generate');

            // Gráfico de sobrevivência 
            Route::get('/graficos/sobrevivencia', 'SobrevivenciaChartController@createSobrevivenciaChart')->name('admin.graficos_sobrevivencia');
            Route::get('/graficos/sobrevivencia/{listagem}', 'SobrevivenciaChartController@createSobrevivenciaChart')->name('admin.graficos_sobrevivencia.listagem');
            Route::post('/graficos/sobrevivencia/generate', 'SobrevivenciaChartController@generateSobrevivenciaChart')->name('admin.graficos_sobrevivencia.to_generate');

            // Gráfico de T.C.A. 
            Route::get('/graficos/tca', 'TcaChartController@createTcaChart')->name('admin.graficos_tca');
            Route::get('/graficos/tca/{listagem}', 'TcaChartController@createTcaChart')->name('admin.graficos_tca.listagem');
            Route::post('/graficos/tca/generate', 'TcaChartController@generateTcaChart')->name('admin.graficos_tca.to_generate');

            // Gráfico de classificação 
            Route::get('/graficos/classificacao', 'ClassificacaoChartController@createClassificacaoChart')->name('admin.graficos_classificacao');
            Route::get('/graficos/classificacao/{listagem}', 'ClassificacaoChartController@createClassificacaoChart')->name('admin.graficos_classificacao.listagem');
            Route::post('/graficos/classificacao/generate', 'ClassificacaoChartController@generateClassificacaoChart')->name('admin.graficos_classificacao.to_generate');

            // Gráfico de coeficiente de variação
            Route::get('/graficos/coeficiente_variacao', 'CoeficienteVariacaoChartController@createCoeficienteVariacaoChart')->name('admin.graficos_coeficiente_variacao');
            Route::get('/graficos/coeficiente_variacao/sobrevivencia/{listagem}', 'CoeficienteVariacaoChartController@createCoeficienteVariacaoChart')->name('admin.graficos_coeficiente_variacao.listagem');
            Route::post('/graficos/coeficiente_variacao/generate', 'CoeficienteVariacaoChartController@generateCoeficienteVariacaoChart')->name('admin.graficos_coeficiente_variacao.to_generate');
            
        });

    });

});

/*
|--------------------------------------------------------------------------
| Rotas que não necessitam de autenticação de usuário para acesso
|--------------------------------------------------------------------------
*/
Route::namespace('Admin')->group(function () {

    Route::get('/planilhas/biometrias', 'BiometriaParcialController@createBiometriaParcial')->name('planilhas.amostras.biometria');

});
