<?php
namespace App\Services;

use App\Services\Contracts\WebhookServiceInterface;
use Illuminate\Support\Facades\Http;

class WebhookService implements WebhookServiceInterface
{
    public function sendContact(array $data): void
    {
        $url = config('services.webhook.url');
        Http::post($url, $data);
    }
}
