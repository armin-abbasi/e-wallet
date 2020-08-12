<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\SocialAccountsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * @param string $provider
     * @return mixed
     */
    public function login(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleCallback(string $provider)
    {
        $providerUser = Socialite::driver($provider)->stateless()->user();

        $user = (new SocialAccountsService())
            ->findOrCreate($providerUser, $provider);

        auth()->login($user);

        return redirect()->route('main');
    }
}
