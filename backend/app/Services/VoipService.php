<?php

namespace App\Services;

use App\Services\Contracts\VoipServiceInterface;
use Twilio\Rest\Client as TwilioClient;


class VoipService implements VoipServiceInterface
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new TwilioClient(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function call($to, $url = 'http://demo.twilio.com/docs/voice.xml')
    {
        return $this->twilio->calls->create(
            $to,
            config('services.twilio.from'),
            ['url' => $url]
        );
    }
}
