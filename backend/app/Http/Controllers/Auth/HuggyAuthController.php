<?php

// app/Http/Controllers/Auth/HuggyAuthController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\HuggyApiService;
use App\Services\HuggyOAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class HuggyAuthController extends Controller
{
    public function __construct(private HuggyOAuthService $oauth) {}

    public function redirect()
    {
        return response()->json([
            'authorize_url' => $this->oauth->authorizeUrl(),
        ]);
    }

    //TODO: tendo muita responsabilidades, aplicar testes e entao extrair para services e melhorar entendimento.
    public function callback(Request $request)
    {
        $code = $request->query('code');
        abort_unless($code, 400, 'missing code');

        $tokens = $this->oauth->exchangeCode($code);

        $access = $tokens['access_token'];
        $refresh = $tokens['refresh_token'] ?? null;
        $expiresIn = $tokens['expires_in'] ?? null;

        // Captura perfil p/ vincular usuário local
        $api = new HuggyApiService($access);
        $profile = $api->me(); // retorna info do agente/empresa

        // Monte esses campos conforme a resposta do /agents/profile
        $huggyUserId = (string) data_get($profile, 'id');
        $huggyCompanyId = (string) data_get($profile, 'companyID');
        $huggyUserEmail = (string) data_get($profile, 'email');

        // Cria/atualiza user local
        $user = User::updateOrCreate(
            ['email' => $huggyUserEmail, 'huggy_user_id' => $huggyUserId],
            [
                'name' => data_get($profile, 'name', 'Huggy Agent'),
                'email' => $huggyUserEmail,
                'password' => 'password',//remover posteriormente ou usar outra estrategia
                'huggy_user_id' => $huggyUserId,
                'huggy_company_id' => $huggyCompanyId,
                'huggy_access_token' => $access,
                'huggy_refresh_token' => $refresh,
                'huggy_expires_at' => $expiresIn ? now()->addSeconds($expiresIn) : null,
            ]
        );

        // Autentica e emite token da API (Sanctum)
        Auth::login($user);
        $apiToken = $user->createToken('api')->plainTextToken;

        // Redireciona para cliente/frontend com code para troca por token sanctum!
        $code = Str::uuid()->toString();
        Cache::put("auth_code_{$code}", $apiToken, now()->addMinute());
        return redirect()->away(config('services.client.callback_redirect') . '?code=' . $code);
    }

    public function exchangeCode(Request $request)
    {
        $code = $request->input('code');
        $apiToken = Cache::pull("auth_code_{$code}"); // Consome o código uma vez só

        if (!$apiToken) {
            return response()->json(['error' => 'Código inválido ou expirado'], 400);
        }

        return response()->json([
            'message' => 'Authenticated with Huggy',
            'api_token' => $apiToken,
        ]);
    }
}

