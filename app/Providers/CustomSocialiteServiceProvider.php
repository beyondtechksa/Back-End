<?php

namespace App\Providers;

use Laravel\Socialite\SocialiteManager;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Apple\Provider;

class CustomSocialiteServiceProvider extends SocialiteManager
{
    public function register()
    {
        $this->app->extend('Laravel\Socialite\Contracts\Factory', function ($app) {
            $socialite = $app->make('Laravel\Socialite\Contracts\Factory');
            $socialite->extend('apple', function ($app) use ($socialite) {
                return $socialite->buildProvider(Provider::class, [
                    'client_id' => config('services.apple.client_id'),
                    'client_secret' => config('services.apple.client_secret'),
                    'redirect' => config('services.apple.redirect'),
                    'team_id' => config('services.apple.team_id'),
                    'key_id' => config('services.apple.key_id'),
                    'private_key_path' => config('services.apple.private_key_path'),
                ]);
            });

            return $socialite;
        });
    }
}