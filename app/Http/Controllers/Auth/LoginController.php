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
use Illuminate\Validation\ValidationException;
use Validator;

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
        $field = (filter_var(request()->email, FILTER_VALIDATE_EMAIL) || !request()->email) ? 'email' : 'nip';
        if ($field != 'email')
            $field = is_numeric(request()->email) ? 'nip' : 'email';
        request()->merge([$field => request()->email]);
        return $field;
    }


    public function login(Request $request): Application|Redirector|RedirectResponse
    {
        $field = (filter_var($request->email, FILTER_VALIDATE_EMAIL) || !$request->email)
            ? 'email'
            : 'nip';

        if ($field !== 'email') {
            $field = is_numeric($request->email) ? 'nip' : 'email';
        }

        $request->merge([$field => $request->email]);

        Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ])->validate();

        if (Auth::attempt([$field => $request->$field, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();

            return $this->authenticated($request, Auth::user())
                ?: redirect()->intended($this->redirectPath());
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function authenticated(Request $request, User $user): RedirectResponse|Redirector|Application
    {

        if ($user->hasAnyRole('Technichian', 'Accounting', 'Stocker', 'WKCA', 'KCA', 'NOC')) {
            return redirect('/utility/user-profile/profile-detail');
        }
        activity()->causedBy(Auth::user())->log(Auth::user()->nip . ' ' . Auth::user()->name . ' Melakukan Login');
        return redirect()->intended($this->redirectTo);
    }


    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => 'NIK atau password salah',
        ]);
    }
}
