<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Auth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

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


    public function login(LoginRequest $request): Application|Redirector|RedirectResponse
    {
        $field = (filter_var($request->email, FILTER_VALIDATE_EMAIL) || !$request->email)
            ? 'email'
            : 'nip';

        if ($field !== 'email') {
            $field = is_numeric($request->email) ? 'nip' : 'email';
        }

        $request->merge([$field => $request->email]);

        // Login
        if (Auth::attempt([$field => $request->$field, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();


            return $this->authenticated($request, Auth::user())
                ?: redirect()->intended($this->redirectPath());
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function authenticated(LoginRequest $request, User $user): RedirectResponse|Redirector|Application
    {
        $user->last_login = now();
        $user->save();

        if ($user->hasAnyRole('Technichian', 'Accounting', 'Stocker', 'WKCA', 'KCA', 'NOC')) {
            return redirect('/utility/user-profile/profile-detail');
        }

        return redirect()->intended($this->redirectTo);
    }


    protected function sendFailedLoginResponse(LoginRequest $request)
    {
        throw ValidationException::withMessages([
            $this->username() => 'Kredensial ini tidak cocok dengan catatan kami.',
        ]);
    }
}
