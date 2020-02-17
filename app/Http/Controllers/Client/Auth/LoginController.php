<?php

namespace App\Http\Controllers\Client\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest:client', ['except' => ['logout']]);
    }

    protected $redirectTo = '/client/home';

    protected function guard()
    {
        return Auth::guard('client');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Login the admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function login(Request $request)
    // {
    //     $this->validator($request);

    //     if (Auth::guard('client')->attempt($request->only('username', 'password'), $request->filled('remember'))) {
    //         //Authentication passed...
    //         // return 'true';
    //         return redirect()
    //             ->intended(route('client.home'))
    //             ->with('status', 'You are Logged in as client!');
    //     }

    //     //Authentication failed...
    //     return $this->loginFailed();
    // }

    public function username()
    {
        return 'username';
    }

    protected function credentials(Request $request)
    {
        return ['username' => $request->{$this->username()}, 'password' =>$request->password, 'status' => 1];
    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function logout()
    // {
    //     Auth::guard('client')->logout();
    //     return redirect()
    //         ->route('client.login')
    //         ->with('status', 'Client has been logged out!');
    // }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session($this->guard())->invalidate();

        // $request->session()->forget('client');

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/client/login');
    }

    /**
     * Validate the form data.
     *
     * @param \Illuminate\Http\Request $request
     * @return
     */
    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'username'    => 'required|exists:clients|min:6|max:191',
            'password' => 'required|string|min:4|max:255',
        ];

        //custom validation error messages.
        $messages = [
            'username.exists' => 'These credentials do not match our records.',
        ];

        //validate the request.
        $request->validate($rules, $messages);
    }

    /**
     * Redirect back after a failed login.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function loginFailed()
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Login failed, please try again!');
    }
}
