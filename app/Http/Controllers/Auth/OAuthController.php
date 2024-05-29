<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        abort_if(!in_array($provider, ['facebook', 'google', 'shopify']), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        abort_if(!in_array($provider, ['facebook', 'google', 'shopify']), 404);

        $oauthUser = Socialite::driver($provider)->user();

        $user = User::whereEmail($oauthUser->getEmail())
            ->first();

        if (!$user) {
            $user = User::create([
                'first_name' => $oauthUser->getName(),
                'last_name' => $oauthUser->getNickname() ?? '',
                'email' => $oauthUser->getEmail(),
                'oauth_provider' => $provider,
                'oauth_provider_id' => $oauthUser->getId(),
            ]);

            $user->sendEmailVerificationNotification();

            auth()->login($user);

            return to_route('user.dashboard.index');
        }else if ($user->oauth_provider != $provider && $user->oauth_provider_id != $oauthUser->getId()) {
            flash(trans('Your email is already registered with another account.'), 'error');

            return to_route('login');
        }else {
            auth()->login($user);

            flash(trans('Welcome back :name', ['name' => $user->name]), 'success');

            return to_route('user.dashboard.index');
        }
    }
}
