<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    // Listar todos os clientes com endereço
    public function index()
    {
        $clients = Client::with('address')->get();
        return response()->json($clients);
    }

    // Criar novo cliente com endereço
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|string',
            'age' => 'nullable|integer|min:0',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Criar cliente
        $client = Client::create($request->only(['name','email','phone','photo','age']));

        // Criar endereço
        $client->address()->create($request->only(['city','state']));

        return response()->json($client->load('address'), 201);
    }

    // Mostrar um cliente específico
    public function show($id)
    {
        $client = Client::with('address')->find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

    // Atualizar cliente e endereço
    public function update(Request $request, $id)
    {
        $client = Client::with('address')->find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:clients,email,'.$client->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|string',
            'age' => 'nullable|integer|min:0',
            'city' => 'sometimes|required|string|max:255',
            'state' => 'sometimes|required|string|max:2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $client->update($request->only(['name','email','phone','photo','age']));

        if ($client->address) {
            $client->address->update($request->only(['city','state']));
        } else if ($request->has(['city','state'])) {
            $client->address()->create($request->only(['city','state']));
        }

        return response()->json($client->load('address'));
    }

    // Deletar cliente (endereço é deletado automaticamente)
    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $client->delete();

        return response()->json(['message' => 'Client deleted successfully']);
    }
}
