<?php

// app/Http/Controllers/HuggySyncController.php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\HuggyApiService;
use Illuminate\Http\Request;

class HuggySyncController extends Controller
{
    //TODO: implementar validação de dados, testes e extrair para melhorar responsabilidades.
    public function __invoke(Request $request)
    {
        $user = $request->user(); // requer Sanctum/bearer
        abort_unless($user?->huggy_access_token, 401, 'No Huggy token');

        $api = new HuggyApiService($user->huggy_access_token);

        $page = 1;
        $imported = 0;
        while (true) {
            $data = $api->listContacts($page, 100);//TODO: nao tem paginacao, analisar otimizacao
            $items = is_array($data) ? $data : data_get($data, 'data', []);
            if (empty($items)) break;

            foreach ($items as $c) {
                $id = (string) data_get($c, 'id');
                $name = data_get($c, 'name');
                $email = data_get($c, 'email');
                $phone = data_get($c, 'phone') ?? data_get($c, 'mobile');
                $city = data_get($c, 'city');
                $state = data_get($c, 'state');
                $photo = data_get($c, 'photo');
                $birth = data_get($c, 'birthDate');

                $client = Client::updateOrCreate(
                    ['huggy_contact_id' => $id, 'email' => $email],
                    [
                        'huggy_contact_id' => $id,
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'avatar_url' => $photo,
                        'birthdate' => $birth ? date('Y-m-d', strtotime($birth)) : null,
                    ]
                );
                if ($client) {
                    $client->address()->updateOrCreate(
                        ['client_id' => $client->id],
                        [
                            'city' => $city,
                            'state' => $state,
                        ]
                    );
                }
                $imported++;
            }

            // pare se não tiver mais; ajuste se a API devolver meta de paginação, melhorar isso depois
            if (count($items) < 100) break;
            $page++;
        }

        return response()->json(['imported' => $imported]);
    }
}
