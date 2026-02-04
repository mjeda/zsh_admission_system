<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function getStudents(Request $request)
    {
        // Validate input
        $request->validate([
            'intake' => 'required|string',
            'course_code' => 'required|string',
        ]);

        $intake = $request->input('intake');
        $courseCode = $request->input('course_code');

        $students = DB::table('selections as s')
            ->join('admissions as a', 's.admission_id', '=', 'a.id')
            ->join('courses as c', 's.course_id', '=', 'c.id')
            ->join('intakes as i', 's.intake_id', '=', 'i.id')
            ->join('control_numbers as cn', 'cn.admission_id', '=', 'a.id')
            ->where('c.code', $courseCode)              // dynamic course_code
            ->where('i.title', $intake)                 // dynamic intake
            ->where('i.year', now()->year)              // constant
            ->where('cn.status', 'PAID')                // constant
            ->where('cn.type', 'REGISTRATION')          // constant
            ->where(function($query) {
                $query->where('s.status', 'SELECTED')
                    ->orWhere('s.status', 'SUBMITTED');
            })
            ->select('a.first_name', 'a.middle_name', 'a.last_name', 'a.index_number', 'a.phone', 'a.dob', 'a.gender')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $students
        ]);
    }
}
