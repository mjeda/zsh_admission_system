<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Intake;
use Illuminate\Http\Request;

class IntakeController extends Controller
{
    public function index(Intake $intake){
        $intakes = Intake::paginate(20);
        return view("intakes.index")->with(["intakes" => $intakes, "intake" => $intake]);
    }

    public function store(Request $request){
        $valid = $request->validate([
            "title" => "required|max:75",
            "type" => "required|max:150",
            "expire_date" => "required|date",
            "round" => "required|numeric",
            "year"  => "required|date_format:Y"
        ]);

        Intake::create($valid);
        return redirect()->route("intake.index")->with("success", "Intake created successfully");
    }

    public function update(Request $request, Intake $intake){
        $valid = $request->validate([
            "title" => "required|max:75",
            "type" => "required|max:150",
            "expire_date" => "required|date",
            "round" => "required|numeric",
            "year"  => "required|date_format:Y"
        ]);

        $intake->update($valid);
        return redirect()->route("intake.index")->with("success", "Intake updated successfully");
    }

    public function destroy(Intake $intake){
        $admission = Admission::where('intake_id', $intake->id)->first();
        if($admission){
            return back()->with("error", "Intake In use");
        }

        $intake->delete();
        return back()->with("success", "Intake deleted successfully");
    }

    public function status(Intake $intake)
    {
        if($intake->status == "Open"){
            $intake->status = "Close";
        } else {
            $intake->status = "Open";
        }
        $intake->save();
        return redirect()->back()->with("success", "Intake status updated successfully");
    }
}
