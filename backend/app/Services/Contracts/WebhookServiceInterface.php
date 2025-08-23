<?php

namespace App\Services\Contracts;

interface WebhookServiceInterface
{
    public function sendContact(array $data): void;
}
