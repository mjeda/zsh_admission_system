<?php

namespace App\Http\Livewire\Admission;

use App\Action\FetchInformationAction;
use App\Action\Kulipia;
use App\Jobs\SmsJob;
use App\Models\Admission;
use App\Models\ControlNumber;
use App\Models\Course;
use App\Models\Intake;
use App\Models\Result;
use App\Models\SelectedCourse;
use App\Service\ControlNumberGenerator;
use Livewire\Component;
use App\Rules\OnlyText;

class Form extends Component
{
    public $currentStep = 1, $successMessage = '', $admission;
    # personal information
    public $first_name, $middle_name, $last_name, $gender, $dob, $school_name, $index_number, $index_number_2, $index_number_3;
    # basic information
    public $email, $address, $district, $region, $phone_number;
    # kin information
    public $next_of_kin_name, $next_of_kin_phone, $next_of_kin_email, $next_of_kin_address, $next_of_kin_relation, $next_of_kin_district, $next_of_kin_region;
    # course
    public $courses = [];
    public $selectedCourses = [], $priority;
    public $availableCourseIds = [];
    # Step
    public $step, $kin_step_visible = false, $payment_step_visible = false;
    #
    public $add_index_number = 1, $count = 2, $control_number, $control_number_status;

    public function mount($admission)
    {
        $this->admission = $admission;
        $this->first_name = $this->admission->first_name;
        $this->middle_name = $this->admission->middle_name;
        $this->last_name = $this->admission->last_name;
        $this->gender = $this->admission->gender;
        $this->dob = $this->admission->dob;
        $this->school_name = $this->admission->school_name;
        $this->index_number = $this->admission->index_number;
        $this->index_number_2 = $this->admission->index_number_2;
        $this->phone_number = $this->admission->phone;
        $this->address = $this->admission->address;
        $this->district = $this->admission->district;
        $this->region = $this->admission->region;
        $this->next_of_kin_name = $this->admission->next_of_kin_name;
        $this->next_of_kin_phone = $this->admission->next_of_kin_phone;
        $this->next_of_kin_email = $this->admission->next_of_kin_email;
        $this->next_of_kin_address = $this->admission->next_of_kin_address;
        $this->next_of_kin_relation = $this->admission->next_of_kin_relation;
        $this->next_of_kin_district = $this->admission->next_of_kin_district;
        $this->next_of_kin_region = $this->admission->next_of_kin_region;
        $this->currentStep = $this->admission->step;
        # Control number
        if ($this->admission->controlNumber != null) {
            $this->control_number = $this->admission->controlNumber->control_number;
            $this->control_number_status = $this->admission->controlNumber->status;
        }

        if ($this->admission->region != null) {
            $this->kin_step_visible = true;
        }
        if ($this->admission->next_of_kin_name != null) {
            $this->payment_step_visible = true;
        }

        if ($this->currentStep == 4 and $this->admission->controlNumber->status == 'PAID') {
            $applicable_courses = json_decode($this->admission->course_payload);
            $this->courses = Course::whereIn('id', $applicable_courses)->where('status', 'Open')->where('type', $this->admission->type)->get();

            $this->availableCourseIds = $this->courses->pluck('id')->toArray();
        }

        if ($this->index_number_2 != null || $this->index_number_3 != null){
            $this->add_index_number = 2;
        }
    }

    public function SavePersonal()
    {

        $this->validate([
            "phone_number" => "required|digits:10|regex:/^0\d{9}$/",
            "address" => ['required', 'max:100', new OnlyText],
            "district" => ['required', 'max:100', new OnlyText],
            "dob" => "required|date",
            "region" => ['required', 'max:100', new OnlyText],
            "index_number_2" => "nullable|regex:/^[SP]\d{4}\/\d{4}\/\d{4}$/",
            "index_number_3" => "nullable|regex:/^[SP]\d{4}\/\d{4}\/\d{4}$/",
        ]);
        $this->admission->update([
            "phone" => $this->phone_number,
            "address" => $this->address,
            "district" => $this->district,
            "dob" => $this->dob,
            "region" => $this->region,
            "index_number_2" => $this->index_number_2,
            "index_number_3" => $this->index_number_3,
        ]);

        $this->dispatchBrowserEvent('swal:info', ['title' => 'success', 'text' => 'Personal information saved!']);
        $this->kin_step_visible = true;
    }

    public function addIndex($param)
    {
        $this->add_index_number = $param;
        $this->count = $param + 1;
        if ($this->add_index_number == 1)
            $this->index_number_2 = null;
        if ($this->add_index_number == 2)
            $this->index_number_3 = null;
    }

    public function savekin()
    {
        $this->validate([
            "next_of_kin_phone" => "required|digits:10|regex:/^0\d{9}$/",
            "next_of_kin_name" => ['required', 'max:100', new OnlyText],
            "next_of_kin_email" => "nullable|email|ends_with:gmail.com,yahoo.com,hotmail.com,icloud.com",
            "next_of_kin_address" => ['required', 'max:100', new OnlyText],
            "next_of_kin_relation" => ['required', 'max:100', new OnlyText],
            "next_of_kin_district" => ['required', 'max:100', new OnlyText],
            "next_of_kin_region" => ['required', 'max:100', new OnlyText],
        ]);
        $this->admission->update([
            "next_of_kin_phone" => $this->next_of_kin_phone,
            "next_of_kin_name" => $this->next_of_kin_name,
            "next_of_kin_email" => $this->next_of_kin_email,
            "next_of_kin_address" => $this->next_of_kin_address,
            "next_of_kin_relation" => $this->next_of_kin_relation,
            "next_of_kin_district" => $this->next_of_kin_district,
            "next_of_kin_region" => $this->next_of_kin_region,
        ]);
        $kulipia = new Kulipia(true);
        $response = $kulipia->requestBill(
            [
                "expire_date" => now()->addWeek()->format('Y-m-d'),
                "bill_reference" => $this->admission->id,
                "pay_option" => "EXACT",
                "bill_currency" => 'TZS',
                "payer_name" => $this->admission->first_name . " ". $this->admission->last_name,
                "payer_phone" => $this->admission->phone,
                "bill_amount" => 30000,
                "bill_description" => "admission fee",
            ]
        );
        if ($response == null){
            return false;
        }

        $this->control_number = $response;

        ControlNumber::UpdateOrCreate([
            "admission_id" => $this->admission->id,
        ], [
            "control_number" => $this->control_number,
            "amount" => 30000,
            "expire_date" => today()->addWeek()
        ]);

        $this->dispatchBrowserEvent('swal:info', ['title' => 'success', 'text' => 'Kin information saved!']);
        $this->payment_step_visible = true;
    }

    public function submitForm()
    {
        # save selected course
        foreach($this->selectedCourses as $index => $selected){
            SelectedCourse::updateOrCreate([
                'admission_id' => $this->admission->id,
                'priority' => $index + 1,
            ], [
                'course_id' => $selected['id'],
            ]);
        }

        $intake = Intake::where('status', 'Open')->latest()->first();
        $this->admission->update(
            [
                "status" => "SUBMITTED",
                'intake_id' => $intake->id,
            ]
        );

        # send sms
        SmsJob::dispatch([$this->admission->phone], 'Asante kwa kuwasilisha maombi yako. Tunapenda kukujuza kuwa maombi yako yamepokelewa.');

        # success message
        $this->dispatchBrowserEvent('swal:info', ['title' => 'success', 'text' => 'Saved! Click next to continue']);
        return redirect()->route('portal-admission');
    }

    public function nextStep($step)
    {
        try {
            return $this->processStep($step);
        } catch (\App\Exceptions\ExternalServiceUnavailableException $e) {
            report($e);
            return redirect()->route('service.unavailable', ['service' => $e->service, 'action' => $e->action]);
        }
    }

    private function processStep($step)
    {
        if ($step == 2) {
            //check if index number 2 is ok
          if ($this->index_number_2) {
              $verify = new FetchInformationAction();
              $index = explode('/', $this->index_number_2);
              if (count($index) == 3) {
                  $response = $verify->PersonalHandle($index[0] . '/' . $index[1], $index[2]);

                  if (empty($response) || $response->status->code == 0) {
                      $this->dispatchBrowserEvent('swal:info', ['title' => 'error', 'text' => 'Incorrect Second Index Number']);
                      return false;
                  }
                  //Check if is same person
                  if($response->particulars->first_name != $this->first_name){
                      $this->dispatchBrowserEvent('swal:info', ['title' => 'error', 'text' => 'Use your own numbers only']);
                      return false;
                  }

              } else {
                  $this->dispatchBrowserEvent('swal:info', ['title' => 'error', 'text' => 'Invalid format in Second Index Number']);
                  return false;
              }
          } elseif ($this->index_number_3) {
            $verify = new FetchInformationAction();
            $index = explode('/', $this->index_number_3);
            if (count($index) == 3) {
                $response = $verify->PersonalHandle($index[0] . '/' . $index[1], $index[2]);

                if (empty($response) || $response->status->code == 0) {
                    $this->dispatchBrowserEvent('swal:info', ['title' => 'error', 'text' => 'Incorrect Third Index Number']);
                    return false;
                }
                //Check if is same person
                if($response->particulars->first_name != $this->first_name){
                    $this->dispatchBrowserEvent('swal:info', ['title' => 'error', 'text' => 'Use your own numbers only']);
                    return false;
                }

            } else {
                $this->dispatchBrowserEvent('swal:info', ['title' => 'error', 'text' => 'Invalid format in Third Index Number']);
                return false;
            }
        }

            $this->SavePersonal();
        } elseif ($step == 3) {
            $this->savekin();
        }
        $control_number = $this->admission->controlNumber;
        $results = Result::where('admission_id', $this->admission->id)->first();



        if ($step == 4 and $control_number->status != 'PAID') {
            return;
        }
        $this->admission->update(["step" => $step]);
        $this->currentStep = $step;
        if($step == 4 and $control_number->status == 'PAID'){
            return redirect()->to(route('portal-admission'));
        }
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }

    public function selectCourse($course)
    {
        if(count($this->selectedCourses) <=2)
        {
            $this->selectedCourses[] = $course;
            $this->availableCourseIds = array_diff($this->availableCourseIds, [$course['id']]);
        } else {
            $this->dispatchBrowserEvent('swal:info', ['title' => 'warning', 'text' => 'Your choice not exceed 3']);
        }
    }

    public function render()
    {
        if ($this->currentStep == 4 and $this->admission->controlNumber->status == 'PAID') {
            $applicable_courses = json_decode($this->admission->course_payload);
            $this->courses = Course::whereIn('id', $applicable_courses)->where('status', 'Open')->where('type', $this->admission->type)->get();

            // $this->availableCourseIds = $this->courses->pluck('id')->toArray();
        }

        return view('livewire.admission.form');
    }
}
