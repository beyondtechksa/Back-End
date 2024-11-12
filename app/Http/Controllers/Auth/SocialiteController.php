<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Illuminate\Support\Facades\Http;


class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
        $user = Socialite::driver('google')->stateless()->user();
        $this->loginOrCreateUser($user, 'google');
        return redirect()->route('dashboard');
        }catch (\Exception $e) {

            return redirect('/login')->with('error', 'Google authentication failed or was canceled.');
        }
    }

    public function redirectToApple()
    {
        $clientId = env('APPLE_CLIENT_ID');
        $redirectUri = urlencode(env('APPLE_REDIRECT_URI'));

        $url = "https://appleid.apple.com/auth/authorize?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&scope=name%20email&response_mode=form_post";

        return redirect($url);
    }

    public function handleAppleCallback(Request $request)
    {
        \Log::info('Apple callback', ['method' => $request->method(), 'input' => $request->all()]);
        $code = $request->input('code');
        $clientId = env('APPLE_CLIENT_ID');
        $clientSecret = $this->generateAppleClientSecret();
        $redirectUri = env('APPLE_REDIRECT_URI');

        $response = Http::asForm()->post('https://appleid.apple.com/auth/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        $tokenResponse = $response->json();

        $idToken = $tokenResponse['id_token'];
        $user = $this->getAppleUser($idToken);

        // Find or create the user in your database
        $localUser = User::updateOrCreate(
            ['provider_id' => $user->sub,'email'=>$user->email],
            ['email' => $user->email, 'name' => $user->email]
        );

        // Log the user in
        Auth::login($localUser);

        // Redirect to the intended page
        return redirect()->route('dashboard');
    }

    protected function loginOrCreateUser($providerUser, $provider)
    {
        $user = User::updateOrCreate(
            [
                'provider_id' => $providerUser->getId(),
                'provider' => $provider,
                'email' => $providerUser->getEmail(),
            ],
            [
                'name' => $providerUser->getName() ?? $providerUser->getNickname(),
                'email' => $providerUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $providerUser->getId(),
            ]
        );

        Auth::login($user, true);
    }

    private function getAppleUser($idToken)
    {
        $payload = explode('.', $idToken)[1];
        $decoded = base64_decode($payload);
        return json_decode($decoded);
    }

    private function generateAppleClientSecret()
    {
        $teamId = env('APPLE_TEAM_ID');
        $clientId = env('APPLE_CLIENT_ID');
        $keyId = env('APPLE_KEY_ID');
        $privateKey = file_get_contents(env('APPLE_PRIVATE_KEY_PATH'));

        $config = Configuration::forSymmetricSigner(
            Sha256::create(),
            InMemory::plainText($privateKey)
        );

        $now = new \DateTimeImmutable();
        $expiresAt = $now->modify('+180 days');

        $token = $config->builder()
            ->issuedBy($teamId) // iss
            ->issuedAt($now) // iat
            ->expiresAt($expiresAt) // exp
            ->permittedFor('https://appleid.apple.com') // aud
            ->relatedTo($clientId) // sub
            ->withHeader('kid', $keyId) // kid
            ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }
}
