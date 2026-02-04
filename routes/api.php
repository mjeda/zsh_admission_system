<?php

use App\Http\Controllers\Academic\AcademicPaymentController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Main route to expose to bank;
//Route::post('/validate', function (Request $request){
//
//    $validator = Validator::make($request->all(),  [
//        'BillReqId' => 'required',
//        'BillCtrNum' => 'required',
//        'User'       => 'required',
//        'Password'   => 'required'
//    ]);
//
//    if ($validator->fails()) {
//        return response()->json(
//            [
//                'StatusCode' => '110',
//                'StatusDesc' => 'Missing Required Data',
//            ]);
//    }
//
//    if(substr($request->json('BillCtrNum'),0, 3) == '310' ){
//        return Http::post('https://zsh-payment-api.akibayetu.com/validate',  $request->all());
//    }
//
//
//    $requestData =  Request::create(\route('validate-admission'),'POST', $request->all());
//    return Route::dispatch($requestData);
//
//});

Route::get('/registered/students', [StudentController::class, 'getStudents']);

Route::post('/transact', function (Request $request){

    $validator = Validator::make($request->all(),  [
        'PymtReqId' => 'required',
        'BillCtrNum' => 'required',
        'User'       => 'required',
        'Password'   => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(
            [
                'StatusCode' => '110',
                'StatusDesc' => 'Missing Required Data',
            ]);
    }


    if(substr($request->json('BillCtrNum'),0, 3) == '310' ){
        return Http::post('https://zsh-payment-api.akibayetu.com/transact',  $request->all());
    }


    $requestData =  Request::create(\route('transact-admission'),'POST', $request->all());
    return Route::dispatch($requestData);

});

//admission route
Route::post('/validate_admission', [PaymentController::class, 'verify'])->name('validate-admission');
Route::post('/transact_admission', [PaymentController::class, 'transact'])->name('transact-admission');

// kulipia
Route::post('/receive/payment', function (Request $request){
    return \App\Action\ReceivePayment::handle($request);
});

