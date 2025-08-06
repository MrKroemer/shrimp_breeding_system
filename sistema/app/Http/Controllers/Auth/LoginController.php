<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\SessionGuard;
use App\Models\Usuarios;
use App\Models\Filiais;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /*
    * Determina a quantidade de tentativas de login que o usuário pode efetuar.
    */
    protected $maxAttempts = 2;
    protected $decayMinutes = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /**
         * Ao tentar acessar a url "/", o usuário será redirecionado 
         * para a pagina principal da aplicação, caso esteja autenticado.
         */
        $this->middleware('guest')->except('logout');
    }

    /**
     * Exibe o formulário de login da aplicação
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $filiais = Filiais::get(['id', 'nome']);

        return view('auth.login')
        ->with('filiais', $filiais);
    }

    /**
     * Captura o request do formulário de login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->merge(['password' => md5($request->password)]);

        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {

            $key = $this->throttleKey($request);

            cache()->forget($key.':timer');

            $this->limiter()->hit($key, ($this->decayMinutes * 60));

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Valida o preenchimento dos dados do formulário de login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $username = $this->username();

        $rules = [
            $username  => 'required|string',
            'password' => 'required|string',
            'filial'   => 'required',
        ];

        $messages = [
            "{$username}.required" => 'O nome de usuário deve ser informado.',
            'password.required'    => 'Uma senha de acesso deve ser informada.',
            'filial.required'      => 'A filial da empresa deve ser selecionada.',
        ];
        
        $this->validate($request, $rules, $messages);
    }

    /**
     * Define o atributo que sera utilizado para autenticar o usuario
     * 
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Defini o caminho para o redirecionamento após login, de acordo com a situação do usuário
     * 
     * @param SessionGuard $guard
     * @return string
     */
    public function redirectPath(SessionGuard $guard, $user_modules)
    {
        if ($guard->user()->situacao == 'ON' && $user_modules->isNotEmpty()) {
            return config('adminlte.dashboard_url');
        }

        $guard->logout();

        return config('adminlte.login_url');
    }

    /**
     * Envia a resposta apos o usuario ser autenticado
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $data = $request->except(['_token']);

        $guard = $this->guard();

        $usuario = $guard->user();

        $filial = Filiais::find($data['filial']); // Filial selecionada na página de login

        $user_modules    = $this->retrieveUserModules($usuario, $filial);
        $selected_module = (int) $user_modules->isNotEmpty() ? $user_modules->first()->id : 0;

        $request->session()->regenerate();
        $request->session()->put('_filial',          $filial);
        $request->session()->put('_user_modules',    $user_modules);
        $request->session()->put('_selected_module', $selected_module);

        $this->clearLoginAttempts($request);
        
        $redirectPath = $this->redirectPath($guard, $user_modules);

        if ($redirectPath == config('adminlte.dashboard_url')) {
            return redirect()->intended($redirectPath);
        }
        
        return redirect()->intended($redirectPath)
        ->with('warning', 'O usuário está inativo ou não possui permissões para acessar a filial selecionada. Por favor, entre em contato com suporte.');
    }

    /**
     * Envia a resposta caso as credencias sejam invalidas
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $key = $this->throttleKey($request);

        $retriesLeft = ($this->limiter()->retriesLeft($key, $this->maxAttempts) + 1);
        
        return redirect()->back()
        ->with('warning', "O usuário e/ou senha informados estão incorretos. Você ainda possui {$retriesLeft} tentativas.");
    }

    /**
     * Recupera todos os módulos que o usuário tem algum acesso. Contando também com os grupos que
     * 
     * @param int $usuario_id
     * @return \Illuminate\Support\Collection
     */
    private function retrieveUserModules(Usuarios $usuario, Filiais $filial)
    {
        $modules = [];

        if ($usuario->grupos->isNotEmpty()) {

            foreach ($usuario->grupos as $usuario_grupo) {

                $grupo_filial = $usuario_grupo->grupo->filiais->where('filial_id', $filial->id)->first();

                if (! is_null($grupo_filial)) {

                    if ($grupo_filial->situacao == 'ON') {

                        foreach ($grupo_filial->permissoes as $permissao) {
    
                            $modules[$permissao->modulo_id] = (object) [
                                'id'   => $permissao->modulo_id,
                                'nome' => $permissao->modulo->nome,
                            ];
    
                        }
                    
                    }

                }

            }

        }

        $usuario_filial = $usuario->filiais->where('filial_id', $filial->id)->first();

        if (! is_null($usuario_filial)) {

            if ($usuario_filial->situacao == 'ON') {

                foreach ($usuario_filial->permissoes as $permissao) {

                    $modules[$permissao->modulo_id] = (object) [
                        'id'   => $permissao->modulo_id,
                        'nome' => $permissao->modulo->nome,
                    ];

                }

            }

        }

        return collect($modules)->sortBy('nome');
    }
}
