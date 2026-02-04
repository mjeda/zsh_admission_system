<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Intake;

class AppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function admissionForm()
    {
        $user = auth()->user();
        $admission = $user->admission;

        // Get active intake
        $active_intake = Intake::where([
            'status' => 'Open',
            'type' => $user->type === 'candidate' ? 'Diploma' : $user->type
        ])->latest()->first();

        // Check if user has previous admission in the same intake name and year
        $previous_admission = Admission::where('user_id', $user->id)
            ->whereHas('intake', function ($query) use ($active_intake) {
                $query->where('title', $active_intake->title)
                      ->whereYear('created_at', now()->year);
            })->latest()->first();

        if ($active_intake) {
            if ($previous_admission) {
                // Continue from where user left off
                $admission = $previous_admission;
            } elseif (!$admission || $admission->intake_id != $active_intake->id) {
                // If no admission exists or the intake has changed, create a new admission
                $admission = Admission::create([
                    'first_name' => $admission->first_name ?? $user->first_name,
                    'middle_name' => $admission->middle_name ?? $user->middle_name,
                    'last_name' => $admission->last_name ?? $user->last_name,
                    'gender'=> $admission->gender ?? $user->gender,
                    'school_name' => $admission->school_name ?? null,
                    'index_number' => $admission->index_number ?? null,
                    'phone' => $admission->phone ?? $user->phone,
                    'email' => $user->email,
                    'user_id' => $user->id,
                    'intake_id' => $active_intake->id,
                    'type' => $admission->type
                ]);
            }
        }
        // Reload admission from database
        $admission = $admission ? $admission->fresh() : null;

        if ($admission->status == 'SUBMITTED' || $admission->status == 'SELECTED') {
            return redirect()->to(route('portal-application'));
        }

        return view("portal.admission", compact('admission'));
    }

    public function show(Admission $admission){
        return view("portal.application-detail")->with("admission", $admission);
    }
}