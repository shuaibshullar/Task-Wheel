<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class login extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function post_request(loginRequest $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerate(true);

        $remember = $request->isRememberMe();


        $isLogin = Auth::attempt([
            'email'    =>    $request->email,
            'password' =>    $request->password,
        ], $remember);

        $request->session()->regenerateToken();


        if ($isLogin)
        {
            Password::deleteToken(Auth::user());

            return to_route('home');
        }
        else
        {
            return to_route('login_get')->withInput()->with('error', true);
        }
    }
}
