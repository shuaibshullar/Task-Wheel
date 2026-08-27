<?php

namespace App\Http\Controllers;

use App\Http\Requests\registerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;


class register extends Controller
{
    public function show()
    {
        /** "$DontShowLoginLink" for register an admin when isn't any admin is users table. */
        $DontShowLoginLink = $this->registerPageForAdmin('makeAdmin');
        return view('auth.register', [
            'DontShowLoginLink' => $DontShowLoginLink
        ]);
    }

    public function post_request(registerRequest $request)
    {
        $user = User::add(
            $request->name,
            $request->email,
            $request->password,
        );

        if (! $user) $this->error($request);

        if ($this->registerPageForAdmin()) // Make admin account
            $user->makeAdmin();

        $request->session()->invalidate();
        $request->session()->regenerate(true);

        Auth::login($user, false);

        $request->session()->regenerateToken();

        return to_route('home');
    }

    private function registerPageForAdmin(?string $key = null): bool
    {
        $makeAdminRegisterPage = ! User::isAnyAdminSet();


        if (! is_null($key))
            View::share($key, $makeAdminRegisterPage);


        return $makeAdminRegisterPage;
    }

    private function error(registerRequest $request)
    {
        abort(500);
    }
}
