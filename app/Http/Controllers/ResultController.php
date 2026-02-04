<?php

namespace App\Http\Controllers;

use App\Action\FetchInformationAction;
use App\Action\ResultValidateAction;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResultController extends Controller
{
    public function index(FetchInformationAction $informationAction)
    {
        $data = $informationAction->ResultsHandle('s1119-0076', 2009);

        if($data){
                Result::create([
                    'admission_id' => 1,
                    'result_payload' => json_encode($data),
                    'status' => 'CREATED'
                ]);
        }
    }

}
