<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialProviderController
{

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @param $provider
     * @return RedirectResponse
     */
    public function redirect($provider): RedirectResponse
    {
        $parameters = ['access_type' => 'offline'];

        $response = Socialite::driver($provider)
            ->scopes(config("{$provider}_apis.scopes"))
            ->with($parameters);
        if (request()->expectsJson()) {
            $response->stateless();
        }
        return $response->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @param $provider
     * @return JsonResponse|RedirectResponse
     */
    public function googleCallback($provider): JsonResponse|RedirectResponse
    {

        if (request()->expectsJson()) {
            $provider_user = Socialite::driver($provider)->stateless()->user();
        }
        else {
            $provider_user = Socialite::driver($provider)->user();
            $current_user = User::whereHas('providers', function ($query) use ($provider, $provider_user) {
                $query->where('provider', $provider)
                    ->where('provider_id', $provider_user->getId());
            })->first();
            if ($current_user) {
                Auth::login($current_user);
                return redirect()->intended('dashboard');
            }
        }


        $user = User::firstOrCreate(
            [
                'email' => $provider_user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $provider_user->getName(),
                'email' => $provider_user->getEmail(),
            ]
        );
        $user->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $provider_user->getId(),
            ],
            [
                'avatar' => $provider_user->getAvatar()
            ]
        );

        Auth::login($user);

        if (request()->expectsJson()) {
            $token = $user->createToken('token-name')->plainTextToken;
            return response()->json($user, 200, ['Access-Token' => $token]);
        }

        return redirect('/dashboard');
    }
}
