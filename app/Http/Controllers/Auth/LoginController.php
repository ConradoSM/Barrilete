<?php

namespace barrilete\Http\Controllers\Auth;

use Auth;
use barrilete\Http\Controllers\Controller;
use barrilete\Role;
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

    /**
     * Default Redirect
     */
    const DEFAULT_REDIRECT = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect To After Login
     * @return string
     */
    public function redirectTo()
    {
        $userRole = Auth::user()->roles()->first() ? Auth::user()->roles()->first()->name : null;
        $roles = Role::exists() ? Role::get() : false;
        if ($roles) {
            foreach ($roles as $role) {
                if ($role->name == $userRole) {
                    return $role->redirectTo;
                }
            }
        }
        return self::DEFAULT_REDIRECT;
    }
}
