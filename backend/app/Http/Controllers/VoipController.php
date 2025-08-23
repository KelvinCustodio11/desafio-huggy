<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\VoipService;
use Illuminate\Http\JsonResponse;

class VoipController extends Controller
{
    public function call(Client $client, VoipService $voip): JsonResponse
    {
        if (!$client->phone) {
            return response()->json(['error' => 'Contato sem telefone'], 400);
        }

        $voip->call($client->phone);

        return response()->json(['message' => 'Ligação iniciada']);
    }
}
