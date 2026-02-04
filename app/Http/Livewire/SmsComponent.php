<?php

namespace App\Http\Livewire;

use App\Jobs\SendRegistrationControlNumberJob;
use App\Action\RegistrationControlNumber;
use App\Jobs\SmsJob;
use App\Models\Admission;
use App\Models\Intake;
use Livewire\Component;

class SmsComponent extends Component
{
    public $student, $status, $intake, $message;
    public $students = [], $admissions = [];

    public function filterStudent(){
        # code...
        if ($this->intake != null and $this->status != null) {
            # code...
            $this->admissions = Admission::where('status', $this->status)
            ->where('intake_id', $this->intake)
            ->where('step', '>=', 2)->get();
        }
    }

    public function cancel($index){
        unset($this->admissions[$index]);
        $this->admissions;
    }

    public function sendSms()
    {
        // Validate the form data
        $validatedData = $this->validate([
            'status' => 'required',
            'intake' => 'required',
            'message' => 'required|string|max:550',
        ]);
        // Send SMS logic goes here
        // $lastStudent = $this->admissions->values()->get(80);
        
        // SendRegistrationControlNumberJob::dispatch($lastStudent);
        // $control_number = (new RegistrationControlNumber($lastStudent['id']))->generate();
        // $message = $this->message . " " . $control_number;
        // SmsJob::dispatch([$lastStudent['phone']], $message);
        // dd($lastStudent);
        foreach ($this->admissions as $student) {
            SmsJob::dispatch([$student['phone']], $this->message);

            // SendRegistrationControlNumberJob::dispatch($student);
        }

        // Redirect back with a success message
        return redirect()->route("send.sms.form")->with("success", "SMS sent successfully.");
    }

    public function render()
    {

        return view('livewire.sms-component', [
            'intakes' => Intake::orderBy('id', 'DESC')->get(),
        ]);
    }
}
