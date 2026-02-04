<?php

namespace App\Http\Controllers;

use App\Action\FetchInformationAction;
use App\Action\ReloadResult;
use App\Action\ResultValidateAction;
use App\Jobs\getResultJob;
use App\Models\Admission;
use App\Models\Course;
use App\Models\Result;
use App\Models\SelectedCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdmissionController extends Controller
{
    public function index(Request $request){
        $status = $request->get('status');
        $intake = $request->get('intake');
        $keyword = $request->get('keyword');
        $admissions = Admission::query()
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($intake, function ($query, $intake) {
                $query->where('intake_id', $intake);
            })->search($keyword)->latest()
            ->paginate()
            ->withQueryString();

        return view("admissions.index")->with("admissions", $admissions);
    }

    public function show(Admission $admission){
        $admission->load('results');
        $courses = Course::all();
        return view("admissions.show", compact('courses'))->with("admission", $admission);
    }

    public function update(Admission $admission, ReloadResult $resultService)
    {
        //get result from NECTA
        $resultService->reload($admission);

        //extra index
        if($admission->index_number_2){
            $resultService->reload($admission, $admission->index_number_2 );
        }

        if($admission->index_number_3){
            $resultService->reload($admission, $admission->index_number_3 );
        }

        return back()->with('success', 'Result fetched again');
    }

    public function changeCourse(Request $request, SelectedCourse $selected_course)
    {
        $request->validate([
            'course' => 'required'
        ]) ;

        $selected_course->course_id = $request->post('course');
        $selected_course->save();

        return back()->with('success', 'Course Changed');
    }

    public function updatedInfo(Request $request, Admission $admission){
        $valid = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|min:10|numeric'
        ]);

        $admission->update($valid);
        $admission->user->update(['email' => $request->input('email')]);

        return redirect()->back()->with('success', 'Updated successfully');
    }

    public function resetPassword(User $user){
        $user->update([
            'password' => Hash::make('zsh-'.date('Y'))
        ]);

        return redirect()->back()->with('success', 'Password reset successful. the new password is zsh-'.date('Y'));
    }
}
