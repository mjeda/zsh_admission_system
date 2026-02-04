<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message, $number;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($number, $message)
    {
        $this->number = $number;
        $this->message    = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = array(
            'sender_id' => 'ZSH',
            'message' => $this->message,
            'recipient' => $this->number,
            'service_id' => 73,
            'api_token' => '9b4d1d9e-b0c1-4fc5-b5e6-db6243c55d84'
        );

        $response = Http::withoutVerifying()->post('https://beep.zmotion.co.tz/api/message/send', $data);
    //    \Log::info($response->body());
    }
}