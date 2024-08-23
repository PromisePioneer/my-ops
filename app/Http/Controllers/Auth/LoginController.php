<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

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

    /**
     * Where to redirect users after login.
     */
    protected string $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function username(): string
    {
        return 'nip';
    }


    public function authenticated(Request $request, User $user): RedirectResponse|Redirector|Application
    {
        if (Auth::user()->status_active === 'Tidak Aktif') {
            Auth::logout();
            return redirect('login')->withErrors(['Your account is inactive']);
        }


        if ($user->hasAnyRole('Technichian', 'Accounting', 'Stocker', 'WKCA', 'KCA', 'NOC')) {
            return redirect('/utility/user-profile/profile-detail');
        }


        return redirect()->intended($this->redirectTo);
    }
}
