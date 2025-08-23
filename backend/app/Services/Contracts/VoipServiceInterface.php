<?php

namespace App\Services\Contracts;

interface VoipServiceInterface
{
    public function call($to, $url = 'http://demo.twilio.com/docs/voice.xml');
}
