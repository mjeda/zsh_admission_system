<?php

namespace App\Http\Controllers;

use App\Models\ControlNumber;
use Illuminate\Http\Request;
use App\Action\Kulipia;

class ControlNumberController extends Controller
{
    public function index(Request $request){

        $search = $request->get('keyword','');
        $control_numbers = ControlNumber::query()
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('admission', function ($sub) use ($search) {
                    $sub->where('index_number', 'like', '%' . $search . '%');
                })
                ->orWhere('control_number', 'like', '%' . $search . '%');
            });
        })
        ->with('admission')
        ->latest()
        ->paginate()
        ->withQueryString();

        return view('control-numbers.index')->with("control_numbers", $control_numbers);
    }

    public function extendExpireDate(ControlNumber $control_number){
        $new_date = now()->addWeek()->format('Y-m-d');

        $kulipia = new Kulipia(true);
        $response = $kulipia->extendBill($control_number->control_number, $new_date);
        
        if ($response->ok()) {
            $control_number->expire_date = $new_date;
            $control_number->save();

            $message = "Operation Successful";
            $message_type = "success";
        } else {
            $message = "Operation failed";
            $message_type = "error";
        }
        return redirect()->route('control-numbers')->with($message_type, $message);
    }
}
