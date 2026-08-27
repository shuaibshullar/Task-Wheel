<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class logout extends Controller
{
    public function logout(Request $request)
    {
        if (Auth::check()) Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerate(true);
        $request->session()->regenerateToken();


        return to_route('login_get');
    }
}
