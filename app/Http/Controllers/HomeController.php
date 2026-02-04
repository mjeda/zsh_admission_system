<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Course;
use App\Models\Intake;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(auth()->user()->type == "candidate") {
            return redirect()->route('portal-dashboard');
        } elseif (auth()->user()->type == "VTA/VETA") {
            return redirect()->route('portal-admission', 'VTA/VETA');
        }

        $veta = Intake::where('type', 'VTA/VETA')->where('status', 'Open')->latest()->first('status');
        $diploma = Intake::where('type', 'Diploma')->where('status', 'Open')->latest()->first('status');

        return view('home', compact('diploma', 'veta'));
    }

    public function dashboard($type, Request $request)
    {
        if ($request->input('intake')){
            $last_intake = Intake::findOrFail($request->input('intake'));
        } else {
            $last_intake = Intake::query()->latest()->first();
        }

        $admissions = Admission::query()->where('intake_id', $last_intake?->id)->get();
        $total_admission = $admissions->count();
//        $submitted_admission = $admissions->where('status', 'SUBMITTED')->count();
        $submitted_admission = $admissions->where('status', '!=', 'CREATED')->count();
        $created_admission = $admissions->where('status', 'CREATED')->count();

        $today_admissions = Admission::query()->latest()->whereDate('created_at', date('Y-m-d'))
            ->where('intake_id', $last_intake?->id)
            ->paginate(10);

        $last_intake_id = $last_intake->id;
        $course_name = $course_value = $first_choice = $second_choice = $third_choice = array();
        $courses = Course::getCoursesWithAdmissionCounts($last_intake_id);
        # loop to add in array
        foreach ($courses as $course){
            $course_name[] = $course->tag;
            $course_value[] = $course->select_admission_course_count;
            $first_choice[] = $course->first_select_admission_course_count;
            $second_choice[] = $course->second_select_admission_course_count;
            $third_choice[] = $course->third_select_admission_course_count;
        }

        $selected_course_name = $selected_course_value = array();
        $selected_courses = Course::getCoursesWithSelectedStudentCount($last_intake_id);
        # loop to add in array
        foreach ($selected_courses as $course){
            $selected_course_name[] = $course->tag;
            $selected_course_value[] = $course->selected_student_course_count;
        }

        $intakes = Intake::all()->sortByDesc('created_at');

        return view('dashboard', compact(
            'total_admission',
            'submitted_admission', 'last_intake',
            'created_admission', 'today_admissions',
            'course_name', 'course_value', 'intakes',
            'selected_course_name', 'selected_course_value',
            'first_choice', 'second_choice', 'third_choice',
        ));
    }
}
