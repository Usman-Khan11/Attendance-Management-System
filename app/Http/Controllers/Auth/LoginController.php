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
            'password' => 'required|string'
        ];

        $request->validate($validation_rule);
    }

    public function authenticated($request)
    {
        $credentials = $request->only($this->username(), 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            $user = auth()->user();

            if ($user->status == 0) {
                Auth::guard('web')->logout();
                return back()->withError('Your account has been deactivated.');
            }

            $ip = $_SERVER["REMOTE_ADDR"];
            $exist = UserLogin::where('user_ip', $ip)->first();
            $userLogin = new UserLogin();
            if ($exist) {
                $userLogin->longitude =  $exist->longitude;
                $userLogin->latitude =  $exist->latitude;
                $userLogin->location =  $exist->location;
                $userLogin->country_code = $exist->country_code;
                $userLogin->country =  $exist->country;
            } else {
                $info = json_decode(json_encode(getIpInfo()), true);
                $userLogin->longitude =  @implode(',', $info['long']);
                $userLogin->latitude =  @implode(',', $info['lat']);
                $userLogin->location =  @implode(',', $info['city']) . (" - " . @implode(',', $info['area']) . "- ") . @implode(',', $info['country']) . (" - " . @implode(',', $info['code']) . " ");
                $userLogin->country_code = @implode(',', $info['code']);
                $userLogin->country =  @implode(',', $info['country']);
            }

            $userAgent = osBrowser();
            $userLogin->user_id = $user->id;
            $userLogin->user_ip =  $ip;
            $userLogin->browser = @$userAgent['browser'];
            $userLogin->os = @$userAgent['os_platform'];
            $userLogin->save();

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
