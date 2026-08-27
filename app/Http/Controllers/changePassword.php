<?php

namespace App\Http\Controllers;

use App\Http\Requests\changePasswordRequest as PasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class changePassword extends Controller
{
    public function show(Request $request)
    {
        return view('auth.password.reset', [
            'mustChangePassword' => true
        ]);
    }

    public function changePassword(PasswordRequest $request)
    {
        $user         = Auth::user();
        $isRememberMe = request()->hasCookie(Auth::getRecallerName());

        $isPasswordChanged = $user->forceFill([
            'password' => $request->password,
            'remember_token' => null,
        ])->save();

        if ($isPasswordChanged)
            $user->passwordIsChanged();

        $request->session()->invalidate();
        $request->session()->regenerate(true);
        $request->session()->regenerateToken();

        Auth::login($user, $isRememberMe);

        return redirect()->route('home');
    }
}
