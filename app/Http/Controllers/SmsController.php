<?php

// app/Http/Controllers/SmsController.php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index()
    {
        return view('sms.index');
    }
}

