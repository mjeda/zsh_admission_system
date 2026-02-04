<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCriteria;
use Illuminate\Http\Request;

class CourseCriteriaController extends Controller
{
    public function index(Course $course, CourseCriteria $courseCriteria){
        $criteria = $course->courseCriterias()->paginate(10);
        return view("criteria.index")->with(["criteria" => $criteria, "course" => $course, "course_criteria" => $courseCriteria]);
    }

    public function store(Request $request, Course $course){
        $valid = $request->validate([
            "title" => "required|max:150",
            "value" => "required|max:1",
            "filter" => "required|in:Include,Exclude,Advantage,Alternative",
            "alternative_for" => "nullable|required_if:filter,Alternative|max:150"
        ]);

        $course->courseCriterias()->create($valid);
        return back()->with("success", "Course criteria created successfully");
    }

    public function update(Request $request, CourseCriteria $courseCriteria){
        $valid = $request->validate([
            "title" => "required|max:150",
            "value" => "required|max:1",
            "filter" => "required|in:Include,Exclude,Advantage,Alternative",
            "alternative_for" => "nullable|required_if:filter,Alternative|max:150"
        ]);

        $courseCriteria->update($valid);
        return redirect()->route("criteria.index", $courseCriteria->course_id)->with("success", "Course criteria updated successfully");
    }

    public function show(CourseCriteria $courseCriteria){
        dd($courseCriteria);
    }

    public function destroy(CourseCriteria $courseCriteria){
        $courseCriteria->delete();
        return back()->with("success", "Course criteria deleted successfully");
    }
}
