<?php

namespace App\Jobs;

use App\Mail\WelcomeMail;
use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected Client $client;

    /**
     * Create a new job instance.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->client->email)->send(new WelcomeMail($this->client));
    }
}
