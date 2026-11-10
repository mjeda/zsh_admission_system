<?php


namespace App\Action;


use App\Models\Course;
use App\Models\Intake;
use App\Models\Selection;
use App\Support\ExternalApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class submitNECTA
{
    public function handle($course_id, $intake_id, $receipt)
    {
        $endpoint = "https://www.nacte.go.tz/nacteapi/index.php/api/upload";
       // $endpoint = "http://127.0.0.1:8888/nacted";
        $intake = Intake::find($intake_id);
        $course = Course::find($course_id);

        $selected_students = Selection::with('admission')
            ->where('intake_id', $intake->id)
            ->where('course_id', $course->id)
            ->where('status','SELECTED')
            ->get();


        $students = [];
        if ($selected_students->isNotEmpty()) {
            foreach ($selected_students as $student) {

                $index = explode('/', $student->admission->index_number);

                $index_2 = null;
                if ($student->admission->index_number_2) {
                    $index_2 = explode('/', $student->admission->index_number_2);
                }

                $index_3 = null;
                if ($student->admission->index_number_3) {
                    $index_3 = explode('/', $student->admission->index_number_3);
                }

                $index_number = $index_2 ? $index[0].'/'.$index[1] . ',' .$index_2[0].'/'.$index_2[1] :
                    $index[0].'/'.$index[1];

                $index_number = $index_3 ? $index_number.','.$index_3[0].'/'.$index_3[1] : $index_number;

                //update fake dob if not exist
                if($student->admission->dob == '0000-00-00'){
                    $student->admission->dob = $this->fake_dob($index[2]);
                    $student->admission->push();
                }

                //phone number fixer
                $student_phone = $student->admission->phone;
                $kin_phone = $student->admission->next_of_kin_phone;

                if(strlen($student_phone) <> 10){
                   $student_phone =  $this->phone_clean($student_phone);
                }

                if(strlen($kin_phone) <> 10){
                    $kin_phone =  $this->phone_clean($kin_phone);
                }

                if(!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',$student->admission->email)){
                    $student->admission->email = "info@zsh.ac.tz";
                }

                $students[] = [
                    "particulars" => [
                        "firstname" => $student->admission->first_name ?? '',
                        "secondname" => $student->admission->middle_name ?? '',
                        "surname" => $student->admission->last_name ?? '',
                        "DOB" => $student->admission->dob,
                        "gender" => $student->admission->gender == 'M' ? 'male' : 'female',
                        "impairement" => "None",
                        "form_four_indexnumber" => $index_number,
                        "form_four_year" => $index_2 ? $index[2] . ',' . $index_2[2] : $index[2],
                        "form_six_indexnumber" => '',
                        "form_six_year" => '',
                        "NTA4_reg" => '',
                        "NTA4_grad_year" => '',
                        "NTA5_reg" => '',
                        "NTA5_grad_year" => '',
                        "email_address" => $student->admission->email ?? 'info@gpc.ac.tz',
                        "mobile_number" => $student_phone ?? '0772533061',
                        "address" => $student->admission->address ?? 'Unknown',
                        "region" => $student->admission->region ?? 'Unknown',
                        "district" => $student->admission->district ?? 'Unknown',
                        "nationality" => 'Tanzanian',
                        "next_kin_name" => $student->admission->next_of_kin_name ?? 'Unknown',
                        "next_kin_address" => $student->admission->next_of_kin_address ?? 'Unknown',
                        "next_kin_email_address" => $student->admission->next_of_kin_email ?? 'info@gpc.ac.tz',
                        "next_kin_phone" => $kin_phone ?? '0772533061',
                        "next_kin_region" => $student->admission->next_of_kin_region ?? 'Unknown',
                        "next_kin_relation" => $student->admission->next_of_kin_relation ?? 'Unknown'
                    ]
                ];
            }
        }else{
            return false;
        }

        $header = [
            'authorization' => '3609c1533f00d3960984221e1db474379d83677f',
            'intake' => $intake->title,
            'programme_id' => $course->code,
            'application_year' => strval($intake->year),
            'level' =>  $course->level,
            'payment_reference_number' => $receipt
        ];


        $data = [
            'heading' => $header,
            'students' => $students
        ];


        $response = ExternalApi::call(ExternalApi::NACTE, 'kutuma wanafunzi waliochaguliwa', fn () =>
            Http::withoutVerifying()->timeout(120)->post($endpoint, [$data])
        );

        Log::alert($response->body());

        if ($response->ok() && $response->json('code') != 400) {
            $selected_students->toQuery()->update([
                'status' => 'SUBMITTED'
            ]);
            return true;
        }

        return false;
    }


    private function fake_dob($year_form_four){
        $d = rand(1,28);
        $m = rand(1,12);
        $y = rand(1994,2000);
        $diff = $year_form_four - $y;

        if($diff < 18){
            $y =  $year_form_four - rand(17,20);
        }

        return sprintf("%02d-%02d-%02d", $y, $m, $d);
    }

    private function phone_clean($phone_number)
    {
        $phone = str_replace(' ', '', $phone_number);

        if(substr( $phone, 0, 3 )  == '255'){
            $phone  = '0' . substr($phone, 3, 9);
        }

        if(substr( $phone, 0, 4 )  == '+255'){
            $phone  = '0' . substr($phone, 4, 9);
        }

        return  strlen($phone) != 10 ? 0772533061: $phone ;
    }
}
