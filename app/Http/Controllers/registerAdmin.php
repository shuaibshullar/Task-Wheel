<?php

namespace App\Http\Controllers;

use App\Http\Requests\registerRequest;
use App\Models\User;


class registerAdmin extends Controller
{
    public function register()
    {
        return view('auth.register', [
            'makeAdmin' => true,
            'adminRoute' => true,
            'adminView' => true,
        ]);
    }

    public function registerPost(registerRequest $request)
    {
        $user = User::add(
            $request->name,
            $request->email,
            $request->password,
        );

        if (! $user) $this->error($request);

        $user->makeAdmin();
        $user->mustChangePassword();

        $request->session()->regenerateToken();

        return to_route('home');
    }

    private function error(registerRequest $request)
    {
        abort(500);
    }
}
