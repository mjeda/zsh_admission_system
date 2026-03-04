<?php

namespace App\Jobs;

use App\Action\RegistrationControlNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Jobs\SmsJob;


class SendRegistrationControlNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $student, $message;

    public function __construct($student)
    {
        $this->student = $student;
    }


    public function handle()
    {
        $control_number = (new RegistrationControlNumber($this->student->id))->generate();
        Log::info("registration control number: " . $control_number);
        if ($control_number) {
            $message = "Hongera! Tunayo furaha kukujulisha kuwa maombi yako ya kujiunga na ZANZIBAR SCHOOL OF HEALTH yamekubaliwa";
            SmsJob::dispatch([$this->student->phone], $message);
        }
    }
}
