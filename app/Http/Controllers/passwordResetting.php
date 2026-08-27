<?php

namespace App\Http\Controllers;

use App\Http\Requests\passwordForgotRequest as forgotRequest;
use App\Http\Requests\passwordResetRequest  as resetRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Facades\Password;

class passwordResetting extends Controller
{
    public function passwordForgotPage()
    {
        return view('auth.password.forgot');
    }

    public function passwordForgotPost(forgotRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );


        return $status === Password::RESET_LINK_SENT
            ? back()->with(  'status', __($status)  )
            : back()->withErrors([  'status' => __($status)  ]);

    }



    public function passwordResetPage(string $token, string $email)
    {
        $broker = Password::broker();

        $user   = $broker->getUser([
            'email' => $email
        ]);


        if (  is_null($user)    ||    ! $broker->tokenExists($user, $token)  )
        {
            throw new InvalidSignatureException();
        }

        return view('auth.password.reset', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function passwordResetPost(resetRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($request)
            {
                $user->forceFill([
                    'password'       => $password,
                    'remember_token' => null,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login_get')->with(  'status', __($status)  )
            : back()->withErrors([  'status' => __($status)  ]);
    }
}
