<?php

namespace App\Http\Controllers\Auth;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    public $userTenantID = 0;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }


    public function findUsername()
    {
        $login = request()->input('username');

        $check_if_exist = \App\Models\User::firstWhere("username", $login);

        if($login){

            if($check_if_exist == null){
                abort(404, "This User Is Not Exist In Our Records!");
            }

            if($check_if_exist->profile == null){
                   Profile::create([
                    'user_id' => $check_if_exist->id
                   ]);
            }


            $userQuery = \App\Models\User::firstWhere("username", $login);
            $tenant_id = $userQuery->tenant_id;

            $this->userTenantID = $tenant_id;

            $active = $userQuery->active;

            if($active == 0){
                abort(404, "This Account Is DeActivated");
            }
        }



        $fieldType ='username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
                session()->put('userTenantID', $this->userTenantID);
                // ✅ Regenerate Session ID Securely
                $request->session()->regenerate();
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }


}