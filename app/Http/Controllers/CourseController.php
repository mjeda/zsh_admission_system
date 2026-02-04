<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Course $course){
        $courses = Course::paginate(20);
        return view("courses.index")->with(["courses" => $courses, "course" => $course]);
    }

    public function store(Request $request){
        $valid = $request->validate([
            "code" => "required|max:75|unique:courses",
            "tag" => "required|max:75|unique:courses",
            "type" => "required",
            "name" => "required|max:150",
            "filtered" => "required",
            "passes" => "required|numeric",
        ]);
        Course::create($valid);
        return redirect()->route("course.index")->with("success", "Course created successfully");
    }

    public function update(Request $request, Course $course){
        $valid = $request->validate([
            "code" => "required|max:75",
            "tag" => "required|max:75",
            "name" => "required|max:150",
            "type" => "required|max:150",
            "filtered" => "required",
            "passes" => "required|numeric",
        ]);

        $course->update($valid);
        return redirect()->route("course.index")->with("success", "Course updated successfully");
    }

    public function destroy(Course $course){

        if($course->selectCourse->isNotEmpty()){
            return back()->with('error', 'Course In use');
        }
        $course->delete();
        return back()->with("success", "Course deleted successfully");
    }

    public function status(Course $course)
    {
        if($course->status == "Open"){
            $course->status = "Close";
        } else {
            $course->status = "Open";
        }
        $course->save();
        return redirect()->back()->with("success", "Course status updated successfully");
    }
}
