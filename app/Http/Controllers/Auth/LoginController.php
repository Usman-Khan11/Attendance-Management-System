<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserLogin;

class LoginController extends Controller
{
    protected $redirectTo = 'user/dashboard';
    protected $username;

    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'logoutGet');
        $this->username = $this->findUsername();
    }

    public function showLoginForm()
    {
        $data["page_title"] = "Login";
        return view('user.auth.login', $data);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        return $this->authenticated($request);
    }

    protected function validateLogin(Request $request)
    {
        $validation_rule = [
            $this->username() => 'required|string',
            'password'        => 'required|string'
        ];

        $request->validate($validation_rule);
    }

    public function authenticated($request)
    {
        $credentials = $request->only($this->username(), 'password');
        $remember = $request->has('remember');

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $user = auth()->user();

            if ($user->status == 0) {
                Auth::guard('web')->logout();
                return back()->withError('Your account has been deactivated.');
            }

            $ipInfo = getIpInfo();
            if (!empty($ipInfo) && env('APP_ENV') == 'production') {
                UserLogin::create([
                    'user_id'      => $user->id,
                    'user_ip'      => $ipInfo['ip'],
                    'location'     => $ipInfo['location'],
                    'browser'      => $ipInfo['browser'],
                    'os'           => $ipInfo['os'],
                    'longitude'    => $ipInfo['longitude'],
                    'latitude'     => $ipInfo['latitude'],
                    'country'      => $ipInfo['country'],
                    'country_code' => $ipInfo['country_code']
                ]);
            }

            return redirect()->route('user.home');
        }

        return back()->withError('User not found.');
    }

    public function findUsername()
    {
        $login = request()->input('username');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login')->withSuccess('You have been logged out.');
    }
}
