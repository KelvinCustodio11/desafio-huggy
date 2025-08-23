<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Services\Contracts\ClientServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected ClientServiceInterface $clientService;

    public function __construct(ClientServiceInterface $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->clientService->getAllClients());
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        try {
            $client = $this->clientService->createClient($request->validated());
            return response()->json($client, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create client',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->clientService->getClient($id));
    }

    public function searchByNameOrPhone(Request $request): JsonResponse
    {
        $text = $request->input('text', '');
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $clients = $this->clientService->searchByNameOrPhone($text);
        return response()->json($clients);
    }

    public function update(UpdateClientRequest $request, int $id): JsonResponse
    {
        try {
            $client = $this->clientService->updateClient($id, $request->validated());
            return response()->json($client);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update client',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->clientService->deleteClient($id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete client',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
