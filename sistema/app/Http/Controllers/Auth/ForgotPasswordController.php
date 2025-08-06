<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Rules\UserInformationValidateRule;
use App\Models\Usuarios;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        if ($this->validateData($request)) {

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );

            return $response == Password::RESET_LINK_SENT 
            ? $this->sendResetLinkResponse($response) 
            : $this->sendResetLinkFailedResponse($request, $response);

        }

        return redirect()->back()
        ->with('warning', 'A matrícula e e-mail informados não estão associados ao mesmo usuário. Por favor, verifique os dados e tente novamente.');
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    protected function validateData(Request $request)
    {
        $data = $this->validate($request, [
            'username' => ['required', new UserInformationValidateRule],
            'email'    => ['required', new UserInformationValidateRule],
        ]);

        $data['username'] = trim($data['username']);
        $data['email']    = trim($data['email']);

        $usuario = Usuarios::where('username', $data['username'])
        ->where('email', $data['email'])
        ->get();

        if ($usuario->isEmpty()) {
            return false;
        }

        return true;
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return redirect()->back()
        ->with('success', trans($response));
    }
}
