<?php

namespace App\Jobs;

use App\Action\RegistrationControlNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        // Log::info("registration control number: " . $control_number);
        // if ($control_number) {
        //     $message = "Hongera! Maombi yako yamekubaliwa. Tafadhali lipia Tsh 50,000/- kupitia control number $control_number ili kujisajili. Fika chuoni Unguja au Africana kama upo Pemba kwa maelezo zaidi.";
        //     SmsJob::dispatch([$this->student->phone], $message);
        // }
    }
}
