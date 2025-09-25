<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;


class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        if (empty($user->profile->postal_code) || empty($user->profile->address)) {
            return redirect()->route('profile.edit');
        }

        return redirect()->intended(config('fortify.home'));
    }
}