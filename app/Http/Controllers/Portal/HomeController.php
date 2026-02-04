<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Intake;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $user = Admission::where('user_id', auth()->id())->latest()->first();

        $progress = match($user->step) {
            1 => 25,
            2 => 50,
            3 => 75,
            4 => 100,
            default => 0
        };

        // message kulingana na step
        $message = match($user->step) {
            1 => 'Umeanza maombi, tafadhali kamilisha hatua zinazofuata.',
            2 => 'Umekamilisha hatua ya pili, endelea na hatua inayofuata.',
            3 => 'Karibu kumaliza! Kamilisha hatua ya mwisho.',
            4 => 'Hongera! Umekamilisha maombi yako. Tafadhali subiri uamuzi wa udahili.',
            default => 'Hujaanza maombi bado.'
        };

        return view('portal.dashboard', compact('user', 'progress', 'message'));
    }

    public function application()
    {
        $user = auth()->user();

        // Intake active (mfano ile yenye status = 'OPEN')
        $active_intake = Intake::where('status', 'Open')->latest()->first();

        // Angalia kama user ana application iliyokamilika (step 4, status SUBMITTED) kwa intake hiyo
        $currentApplication = Admission::where('user_id', $user->id)
            ->whereHas('intake', function ($query) use ($active_intake) {
                $query->where('title', $active_intake->title)
                      ->whereYear('created_at', now()->year);
            })->latest()->first();

        // Pata application moja tu aliyoimaliza kwenye intakes zilizopita
        $previousApplications = Admission::where('user_id', $user->id)
        ->whereHas('intake', fn($q) => $q->where('status', 'Close'))
        ->get();

        return view('portal.application', compact('active_intake', 'currentApplication', 'previousApplications'));
    }

}
