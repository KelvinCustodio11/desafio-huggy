<?php

namespace App\Services;

use App\Services\Contracts\HuggyOAuthServiceInterface;
use Illuminate\Support\Facades\Http;

class HuggyOAuthService implements HuggyOAuthServiceInterface
{
    public function authorizeUrl(array $scopes = ['install_app','read_agent_profile']): string
    {
        $query = http_build_query([
            'scope' => implode(' ', $scopes),
            'response_type' => 'code',
            'redirect_uri' => config('services.huggy.redirect'),
            'client_id' => config('services.huggy.client_id'),
        ]);
        return rtrim(config('services.huggy.auth_base'), '/')."/oauth/authorize?{$query}";
    }

    public function exchangeCode(string $code): array
    {
        $resp = Http::asForm()->post(rtrim(config('services.huggy.auth_base'), '/').'/oauth/access_token', [
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('services.huggy.redirect'),
            'client_id' => config('services.huggy.client_id'),
            'client_secret' => config('services.huggy.client_secret'),
            'code' => $code,
        ])->throw()->json();
        return $resp;
    }

    public function refresh(string $refreshToken): array
    {
        $resp = Http::asForm()->post(rtrim(config('services.huggy.auth_base'), '/').'/oauth/access_token', [
            'grant_type' => 'refresh_token',
            'client_id' => config('services.huggy.client_id'),
            'client_secret' => config('services.huggy.client_secret'),
            'refresh_token' => $refreshToken,
        ])->throw()->json();
        return $resp;
    }
}
