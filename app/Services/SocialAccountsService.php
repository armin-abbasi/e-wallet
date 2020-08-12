<?php

namespace App\Services\Auth;

use App\User;
use App\LinkedSocialAccount;
use Illuminate\Database\Eloquent\Model;
use Laravel\Socialite\Two\User as ProviderUser;

class SocialAccountsService
{
    /**
     * Find or create user instance by provider user instance and provider name.
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     *
     * @return User|Model
     */
    public function findOrCreate(ProviderUser $providerUser, string $provider)
    {
        $linkedSocialAccount = LinkedSocialAccount::query()
            ->where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if ($linkedSocialAccount) {
            return $linkedSocialAccount->user;
        }

        $user = User::query()->firstOrCreate([
            'email'      => $providerUser->getEmail(),
            'name'       => $providerUser->getName(),
        ]);

        $user->linkedSocialAccounts()->create([
            'provider_id'   => $providerUser->getId(),
            'provider_name' => $provider,
        ]);

        return $user;
    }
}

