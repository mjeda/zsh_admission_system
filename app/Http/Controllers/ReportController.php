<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Course;
use App\Models\Intake;
use App\Models\Payment;
use App\Models\SelectedCourse;
use App\Models\Selection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function payment(Request $request){
        $method = $request->input('method');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $notify = "";
        if ($method and $start_date and $end_date){
            $payments = Payment::with(['controlNumber', 'user'])->where('payment_method', $method)->whereBetween('payment_date', [$start_date, $end_date])->get();
            $notify = "Showing report of <span style='color:red'>". ucfirst($method) ."</span> payments starting from <span style='color:red'>" . $start_date . "</span> --to-- <span style='color:red'>" . $end_date;
        }
        elseif ($method and ($start_date or $end_date)){
            $payments = Payment::with(['controlNumber', 'user'])->where('payment_method', $method)->where('payment_date', $start_date)->orWhere('payment_date', $end_date)->get();
            $notify = "Showing report of <span style='color:red'>". ucfirst($method) ."</span> payments on <span style='color:red'>" . ($start_date ?? $end_date) ;
        }
        elseif ($start_date and $end_date){
            $payments = Payment::with(['controlNumber', 'user'])->whereBetween('payment_date', [$start_date, $end_date])->get();
            $notify = "Showing report of payments starting from <span style='color:red'>" . $start_date . "</span> --to-- <span style='color:red'>" . $end_date;
        }
        else {
            $payments = Payment::with(['controlNumber', 'user'])->where('payment_method', $method)
                ->orWhere('payment_date', $start_date)->orWhere('payment_date', $start_date)->get();
            if ($method || $start_date || $end_date)
                $notify = "Showing report of payments <b style='color:red'>(" . ($method ?? $start_date ?? $end_date) . ")</b>";
        }
        return view('reports.payments', compact(['payments', 'notify']));
    }

    public function admission(Request $request) {
        $notify = "";
        $status = $request->input('status');
        $start_date = $request->input('start_date');
        if ($start_date) { $start_date = Carbon::parse($start_date)->startOfDay(); }
        $end_date = $request->input('end_date');
        if ($end_date) { $end_date = Carbon::parse($end_date)->endOfDay(); }
        if ($status and $start_date and $end_date){
            $admissions = Admission::with('controlNumber')->where('status', $status)->whereBetween('created_at', [$start_date, $end_date])->get();
            $notify = "Showing report of <span style='color:red'>". ucfirst($status) ."</span> admissions starting from <span style='color:red'>" . $start_date . "</span> --to-- <span style='color:red'>" . $end_date;
        }
        elseif ($status and ($start_date or $end_date)){
            $admissions = Admission::with('controlNumber')->where('status', $status)->whereDate('created_at', $start_date)->orWhereDate('created_at', $end_date)->get();
            $notify = "Showing report of <span style='color:red'>". ucfirst($status) ."</span> admissions on <span style='color:red'>" . ($start_date ?? $end_date) ;
        }
        elseif ($start_date and $end_date){
            $admissions = Admission::with('controlNumber')->whereBetween('created_at', [$start_date, $end_date])->get();
            $notify = "Showing report of admissions starting from <span style='color:red'>" . $start_date . "</span> --to-- <span style='color:red'>" . $end_date;
        }
        else {
            $admissions = Admission::with('controlNumber')->where('status', $status)
                ->orWhereDate('created_at', $start_date)->orWhereDate('created_at', $end_date)->get();
            if ($status || $start_date || $end_date)
                $notify = "Showing report of admissions <b style='color:red'>(" . ($status ?? $start_date ?? $end_date) . ")</b>";
        }
        return view('reports.admissions', compact(['admissions', 'notify']));
    }

    public function admissionByCourse(Request $request)
    {
        $notify = "";
        $status = $request->input('status');
        $course = $request->input('course');
        $intake = $request->input('intake');

        if ($course || $intake || $status) {
            $admissions = Admission::query()
                ->select('admissions.*')
                ->with(['intake', 'selectedCourse.course'])
                ->when($status, fn($q) => $q->where('status', $status))
                ->when($intake, fn($q) => $q->where('intake_id', $intake))
                ->when($course, fn($q) =>
                    $q->whereHas('selectedCourse', fn($sub) => $sub->where('course_id', $course))
                )
                ->distinct()
                ->get();

            $_course = $course ? Course::find($course) : null;
            $_intake = $intake ? Intake::find($intake) : null;

            $notify = "Showing report of admissions";
            if ($_course) {
                $notify .= " for <span style='color:red'>". ucfirst($_course->name) ."</span>";
            }
            if ($_intake) {
                $notify .= " Intake <span style='color:red'>". $_intake->title ." round - ". $_intake->round .", ". $_intake->year ."</span>";
            }
            if ($status) {
                $notify .= " with status <span style='color:red'>". ucfirst($status) ."</span>";
            }
            // dd($admissions);
        } else {
            $admissions = collect([]);
        }

        $intakes = Intake::orderByDesc('id')->get();
        $courses = Course::all();

        return view('reports.admissions_by_courses', compact('admissions', 'notify', 'intakes', 'courses'));

    }


    public function admissionByResults(Request $request)
    {
        $admissions = [];

        if($request->filled('subject_name') || $request->filled('subject_grade')){
            $admissions = Admission::whereHas('results', function($q) use($request){
                $q->whereJsonContains('result_payload->subjects', ['subject_name' => $request->get('subject_name'), 'grade' => $request->get('subject_grade')]);
            })->get();
        }

      return view('reports.search_results', compact('admissions'));

    }

    public function selectionByCourse(Request $request){
        $notify = "";
        $intake = $request->input('intake');
        $course = $request->input('course');
        
        if($request->filled(['course', 'intake'])){
            $admissions = Selection::with("admission", "course")->where('course_id', $course)
            ->where("intake_id", $intake)->get();

            $_course = Course::find($course);
            $_intake = Intake::find($intake);

            $notify = "Showing report of student selection for <span style='color:red'> ". ucfirst($_course->name) ."</span> Intake <span style='color:red'>". $_intake->title. " round - ". $_intake->round .", ". $_intake->year ."</span>";
        }else{
            $admissions = [];
        }

        $intakes = Intake::orderBy('id')->get();
        $courses = Course::all();
        return view('reports.selections', compact(['admissions', 'notify', 'intakes', 'courses']));
    }
}
