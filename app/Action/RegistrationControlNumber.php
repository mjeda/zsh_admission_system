<?php

namespace App\Action;

use App\Models\Admission;
use App\Action\Kulipia;
use App\Models\ControlNumber;

class RegistrationControlNumber {
    public $admission, $control_number;

    public function __construct($admission)
    {
        $this->admission = Admission::find($admission);
    }

    public function generate()
    {
        $kulipia = new Kulipia(true);

        $response = $kulipia->requestBill([
            "expire_date"      => now()->addMonth()->format('Y-m-d'),
            "bill_reference"   => $this->admission->id .'#reg123',
            "pay_option"       => "EXACT",
            "bill_currency"    => 'TZS',
            "payer_name"       => $this->admission->first_name . " " . $this->admission->last_name,
            "payer_phone"      => $this->admission->phone,
            "bill_amount"      => 50000,
            "bill_description" => "Registration Fee",
        ]);

        if (!$response) {
            return false;
        }

        $this->control_number = $response;

        ControlNumber::updateOrCreate(
            [
                "type" => "REGISTRATION",
                "admission_id" => $this->admission->id,
            ],
            [
                "control_number" => $this->control_number,
                "amount"         => 50000,
                "expire_date"    => now()->addMonth(),
                "type" => "REGISTRATION",
            ]
        );

        return $this->control_number;
    }
}
