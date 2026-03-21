<?php

namespace App\Http\Controllers;

use App\Action\ResultValidateAction;
use App\Action\submitNECTA;
use App\Action\viewApprovedNECTA;
use App\Action\viewNECTA;
use App\Models\Admission;
use App\Models\Course;
use App\Models\Intake;
use App\Models\SelectedCourse;
use App\Models\Selection;
use Dflydev\DotAccessData\Data;
use Doctrine\DBAL\Driver\OCI8\Result;
use Illuminate\Console\View\Components\Choice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendRegistrationControlNumberJob;

class SelectedCourseController extends Controller
{
    public function index(Request $request)
    {
        if($request->filled(['course', 'intake', 'choice'])){
            $course_id = $request->get('course');
            $intake_id = $request->get('intake');
            $choice = $request->get('choice');

            $selections = SelectedCourse::with("admission", "course")->where('priority', $choice)->where('course_id', $course_id)
            ->whereHas('admission', function ($query) use ($intake_id){
                    $query->where("intake_id", $intake_id)->where('status', 'SUBMITTED');
            })
            ->get();
        }else{
            $selections = null;
        }


        $passed_selection = [];
        $collection = Collect();
        $result_validate = new ResultValidateAction;

        if ($request->filled(['course', 'intake'])){
            $collection = Selection::where('course_id', $course_id)->where('intake_id', $intake_id)->get();
        }


        if($selections){
            foreach ($selections as $selection) {
                $courses = $result_validate->handle($selection->admission->id, $course_id);
                if (in_array($selection->course_id, $courses)) {
                    if(!$collection->where('admission_id', $selection->admission->id)->first()){
                        $passed_selection[] = $selection;
                    }

                }
            }
        }

        $passed_selection = collect($passed_selection)->sortByDesc('admission.points');

        return view("selections.index", [
            "selections" => $passed_selection,
            "courses" => Course::all(),
            "intakes" => Intake::orderByDesc('id')->get(),
            ]);
    }

    public function create(Request $request)
    {

        if($request->post('selected') && $request->post('course') && $request->post('intake')){
            foreach ($request->post('selected') as $item){
                $selected = Selection::create([
                    'intake_id' => $request->post('intake'),
                    'course_id' => $request->post('course'),
                    'admission_id' =>$item
                ]);

                $selected->admission()->update(['status' => 'SELECTED']);

                SendRegistrationControlNumberJob::dispatch($selected->admission);
            }

            return redirect()->back()->with('success', 'Added to collection');
        }
        return redirect()->back()->with('error', 'No Selection');
    }

    public function show(Request $request)
    {
        $collections = Selection::with('admission', 'course')
            ->where('course_id', $request->course)
            ->where('intake_id', $request->intake)
            ->orderByRaw('(SELECT first_name FROM admissions WHERE admissions.id = selections.admission_id) ASC')
            ->get();


       return view('selections.show', [
           'collections' => $collections,
           'intake' => $request->intake,
           'course' => $request->course
       ]);
    }

    public function store($course, $intake, Request $request, submitNECTA $submitNECTA)
    {
        $request->validate([
            'receipt' => 'required'
        ]);

        $result = $submitNECTA->handle($course,$intake, $request->get('receipt'));

        if(!$result){
            return redirect()->back()->with('error', 'Submission Failed');
        }

        return redirect()->back()->with('success', 'Submitted Successfully');
    }

    public function store_index( $course_id,$intake_id, viewNECTA $viewNECTA)
    {
        $intake = Intake::find($intake_id);
        $course = Course::find($course_id);

        $submissions = $viewNECTA->handle($course, $intake, $intake->year);
        return view('selections.show_index', compact('submissions','intake','course'));
    }

    public function submission_status($course_id,$intake_id, viewApprovedNECTA $approvedNECTA)
    {
        $intake = Intake::find($intake_id);
        $course = Course::find($course_id);

        $submissions = $approvedNECTA->handle($course, $intake, $intake->year);
        return view('selections.show_approval_index', compact('submissions','intake','course'));
    }

    public function destroy(Selection $item)
    {
        $item->admission()->update(['status' => 'SUBMITTED']);
        $item->delete();
        return redirect()->back()->with('success', 'Admission removed from selection');
    }
}
