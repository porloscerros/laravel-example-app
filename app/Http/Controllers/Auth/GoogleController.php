<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController
{
    public function redirect()
    {
        $parameters = ['access_type' => 'offline'];

        return Socialite::driver('google')
            ->scopes(config('google_apis.scopes'))
            ->with($parameters)
            ->redirect();
    }

    public function googleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        if ($current_user = User::where('google_id', $googleUser->id)->first()) {
            Auth::login($current_user);

            return redirect()->intended('dashboard');
        }

        if ($user = User::where('email', $googleUser->email)->first()) {
            $user->update([
                'google_id' => $googleUser->id,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);
        } else {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make(Str::random(8)),
                'google_id' => $googleUser->id,
                // 'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}
