<?php

namespace App\Http\Controllers;

use App\Action\viewApprovedNECTA;
use App\Action\viewFeedbackErrorNACTE;
use App\Action\viewNECTA;
use App\Models\Course;
use App\Models\Intake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class NacteApiController extends Controller
{
    public $respond = [];

    public function feedbackError(Request $request)
    {
        $respond = null;
        if($request->filled(['course', 'intake'])){
            $course = Course::find($request->input('course'));
            $intake = Intake::find($request->input('intake'));
            $year = $intake->year;

            $feedback_error = new viewFeedbackErrorNACTE();
            $respond = $feedback_error->handle($course, $intake, $year);
            Session::put('feedback_errors', $respond->params ?? []);
        }

        return view('nacte.feedback-errors')->with([
            'feedback_errors' => $respond,
            'courses' => Course::all(),
            'intakes' => Intake::orderByDesc('id')->get(),
        ]);
    }

    public function fixError($data)
    {
        $applicant =  Session::get('feedback_errors')[$data];
        return view('nacte.fix-error-form', compact('applicant', 'data'));
    }

    public function uploadFeedbackError(Request $request, $data)
    {
        $intake = Intake::latest()->first();
        $request->validate([
            'email' => 'required|email',
            'phone_number' => 'required',
            'first_name' => 'required|string',
            'second_name' => 'required|string',
            'surname' => 'required|string',
            'form_four_year' => 'required|string',
            'form_four_index' => 'required|string',
        ]);

        $applicant =  Session::get('feedback_errors')[$data];

        $postData = [
            'heading' => [
                'authorization' => '3609c1533f00d3960984221e1db474379d83677f',
                'intake' => $intake->title,
                'programme_id' => $applicant->programme_id,
                'academic_year' => $intake->year,
                'level' => '4'
            ],
            'students' => [
                [
                    'student' => [
                        'student_verification_id' => $applicant->student_verification_id,
                        'firstname' => $request->input('first_name'),
                        'secondname' => $request->input('second_name'),
                        'surname' => $request->input('surname'),
                        'mobile_number' => $request->input('phone_number'),
                        'email_address' => $request->input('email'),
                        'form_four_indexnumber' => $request->input('form_four_index'),
                        'form_four_year' => $request->input('form_four_year'),
                        'form_six_indexnumber' => '',
                        'form_six_year' => '',
                        'NTA4_reg' => '',
                        'NTA4_grad_year' => '',
                        'NTA5_reg' => '',
                        'NTA5_grad_year' => ''
                    ]
                ]
            ]
        ];

        // dd($postData);

        $response = Http::withoutVerifying()->post("https://www.nacte.go.tz/nacteapi/index.php/api/addcorrection", [$postData]);
        if ($response->ok() && $response->json('code') != 400) {
            $message = $response->json('message'); $type = "success";
        } else {
            $message = $response->json('message'); $type = "error";
        }


        return redirect()->route('nacte.feedback-error')->with($type, $message);
    }

    public function getPushedList(Request $request){
        $respond = null;
        if($request->filled(['course', 'intake'])){
            $course = Course::find($request->input('course'));
            $intake = Intake::find($request->input('intake'));
            $year = $intake->year;

            $pushed_list = new viewNECTA();
            $respond = $pushed_list->handle($course, $intake, $year);
        }

        return view('nacte.pushed-list')->with([
            'submitted' => $respond->params ?? [],
            'courses' => Course::all(),
            'intakes' => Intake::orderByDesc('id')->get(),
        ]);
    }

    public function getVerifiedStudent(Request $request){
        $respond = null;
        if($request->filled(['course', 'intake'])){
            $course = Course::find($request->input('course'));
            $intake = Intake::find($request->input('intake'));
            $year = $intake->year;

            $verified_student = new viewApprovedNECTA();
            $respond = $verified_student->handle($course, $intake, $year);
            Session::put('student_enrolled', $respond->params);
        }

        return view('nacte.verified-students')->with([
            'submitted' => $respond->params ?? [],
            'courses' => Course::all(),
            'intakes' => Intake::orderByDesc('id')->get(),
        ]);
    }

    public function enrolStudent($student_verification_id, $programme, $intake, $year) {
        $programme = Course::findOrFail($programme);

        $postData = [
            'authorization' => '3609c1533f00d3960984221e1db474379d83677f',
            'student_verification_id' => $student_verification_id,
            'upload_user' => auth()->user()->name,
            'programme_id'=> $programme->code,
            'intake' => $intake,
            'year' => $year,
        ];

        $response = Http::withoutVerifying()->post("https://www.nacte.go.tz/nacteapi/index.php/api/registerstudent", [$postData]);
        // dd($response->json());
        if ($response->ok() && $response->json('code') == 200) {
            $message = $response->json('message'); $type = "success";
        } else {
            $message = $response->json('message'); $type = "error";
        }

        return redirect()->back()->with($type, $message);
    }

    public function enrolAllStudent($programme, $intake) {
        $programme = Course::findOrFail($programme);
        $intake = Intake::findOrFail($intake);
        $response = []; $student_enrolled = Session::get('student_enrolled');

        foreach ($student_enrolled as $student) {
            $postData = [
                'authorization' => '3609c1533f00d3960984221e1db474379d83677f',
                'student_verification_id' => $student->user_id,
                'upload_user' => auth()->user()->name,
                'programme_id'=> $programme->code,
                'intake' => $student->intake,
                'year' => $student->academic_year,
            ];

            $response = Http::withoutVerifying()->post("https://www.nacte.go.tz/nacteapi/index.php/api/registerstudent", [$postData]);
        }

        if ($response->ok() && $response->json('code') == 200) {
            $message = $response->json('message'); $type = "success";
        } else {
            $message = $response->json('message'); $type = "error";
        }

        return redirect()->route('nacte.verified.student')->with($type, $message);
    }
}
