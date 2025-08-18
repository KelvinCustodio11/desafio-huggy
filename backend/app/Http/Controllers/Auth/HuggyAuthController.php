<?php

// app/Http/Controllers/Auth/HuggyAuthController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\HuggyApiService;
use App\Services\HuggyOAuthService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HuggyAuthController extends Controller
{
    public function __construct(private HuggyOAuthService $oauth) {}

    public function redirect()
    {
        return response()->json([
            'authorize_url' => $this->oauth->authorizeUrl(),
        ]);
    }

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
        $huggyCompanyId = (string) data_get($profile, 'companyId');

        // Cria/atualiza user local
        $user = User::updateOrCreate(
            ['huggy_user_id' => $huggyUserId],
            [
                'name' => data_get($profile, 'name', 'Huggy Agent'),
                'email' => data_get($profile, 'email'),
                'huggy_company_id' => $huggyCompanyId,
                'huggy_access_token' => $access,
                'huggy_refresh_token' => $refresh,
                'huggy_expires_at' => $expiresIn ? now()->addSeconds($expiresIn) : null,
            ]
        );

        // Autentica e emite token da sua API (Sanctum)
        Auth::login($user);
        $apiToken = $user->createToken('api')->plainTextToken;

        return response()->json([
            'message' => 'Authenticated with Huggy',
            'user' => $user->only(['id','name','email','huggy_user_id','huggy_company_id']),
            'api_token' => $apiToken,
        ]);
    }
}

