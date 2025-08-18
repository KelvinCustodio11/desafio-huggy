<?php

namespace App\Services\Contracts;

interface HuggyOAuthServiceInterface
{
    public function authorizeUrl(array $scopes = ['install_app', 'read_agent_profile']): string;
    public function exchangeCode(string $code): array;
    public function refresh(string $refreshToken): array;
}
