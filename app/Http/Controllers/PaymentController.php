<?php

namespace App\Http\Controllers;

use App\Jobs\getResultJob;
use App\Jobs\SmsJob;
use App\Models\ControlNumber;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index(Request $request){
        $search = $request->get('keyword','');
        $payments = Payment::with("controlNumber")
            ->whereHas('controlNumber', function ($query) use ($search) {
                $query->where('control_number', $search);
            })
            ->orWhere('receipt', 'like', "%$search%")
            ->latest()
            ->paginate()
            ->withQueryString();

        return view('payments.index')->with("payments", $payments);
    }

    public function verify(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->json()->get('User') == 'PBZ' && $request->json()->get('Password') == 'zsh_pbz_2023') {

            #Validate
            $this->validate($request, [
                'BillReqId' => 'required',
                'BillCtrNum' => 'required'
            ]);

            #Request Input
            $BillReqId = $request->json()->get('BillReqId');
            $BillCtlNum = $request->json()->get('BillCtrNum');

            $ControlNumber = ControlNumber::where('control_number',$BillCtlNum)->first();


            if ($ControlNumber) {
                //check if already paid
                if ($ControlNumber->status == 'PAID') {
                    return response()->json(
                        [
                            'BillReqId' => $BillReqId,
                            'StatusCode' => '111',
                            'StatusDesc' => 'Bill already settled',
                        ]);
                }

                //check if expired
                if ($ControlNumber->expire_date->isPast()) {
                    return response()->json(
                        [
                            'BillReqId' => $BillReqId,
                            'StatusCode' => '108',
                            'StatusDesc' => 'Bill has expired',
                        ]);
                }


                #Response
                $StatusCode = 100;
                $StatusDesc = 'Success';
                $BillAmt = $ControlNumber->amount;
                $BillCcy = 'TZS';
                $BillExprDt = $ControlNumber->expire_date;
                $BillDesc = str_replace('/','-', $ControlNumber->admission->index_number);
                $BillPymtTyp = 'Admission Fee';
                $BillPymtOpt = 'EXACT';
                $PyrName = $ControlNumber->admission->full_name;
                $PyrMobile = $ControlNumber->phone;

                return response()->json(
                    [
                        'BillReqId' => $BillReqId,
                        'StatusCode' => $StatusCode,
                        'StatusDesc' => $StatusDesc,
                        'BillCtlNum' => $BillCtlNum,
                        'BillAmt' => $BillAmt,
                        'BillCcy' => $BillCcy,
                        'BillExprDt' => $BillExprDt,
                        'BillDesc' => $BillDesc,
                        'BillPymtTyp' => $BillPymtTyp,
                        'BillPymtOpt' => $BillPymtOpt,
                        'PyrName' => $PyrName,
                        'PyrMobile' => $PyrMobile
                    ]);

            } else {

                return response()->json(
                    [
                        'BillReqId' => $BillReqId,
                        'StatusCode' => '110',
                        'StatusDesc' => 'Invalid request data',
                    ]);

            }
            //end of auth
        } else {
            return response()->json(
                [
                    'StatusCode' => '106',
                    'StatusDesc' => 'Unauthorized',
                ]);
        }
    }


    public function transact(Request $request)
    {
        if ($request->has('isCash') || $request->json()->get('User') == 'PBZ' && $request->json()->get('Password') == 'zsh_pbz_2023') {
            #validate required data

            if($request->has('isCash')){

                $request->merge(
                    [
                        'PymtReqId' => 'RT'.rand(100,200).date('ymdhms'),
                        'TrxId' => 'None',
                        'PayRefId' => 'Ref'.rand(100,200).date('ymdhms'),
                        'BillCtrNum' => $request->get('control_number'),
                        'TrxDtTm' => today(),
                        'PaidAmt' =>  30000,
                        'Ccy' => 'TZS',
                        'AccNum' => 'NONE',
                        'PyrName' => 'None'
                    ]
                );
            }

            $this->validate($request, [
                'PymtReqId' => 'required',
                'TrxId' => 'required',
                'PayRefId' => 'required',
                'BillCtrNum' => 'required',
                'TrxDtTm' => 'required',
                'PaidAmt' => 'required',
                'Ccy' => 'required',
                'AccNum' => 'required',
                'PyrName' => 'required'
            ]);

            #Onfly Validate
            $ControlNumber = ControlNumber::where('control_number',$request->BillCtrNum)->first();

            #Data from request
            $BillCtrNum = $request->BillCtrNum;
            $BillPymtReqId = $request->PymtReqId;
            $BillTrxId = $request->TrxId;
            $BillPayRefId = $request->PayRefId;
            $PaidAmt = $request->has('isCash') ? $ControlNumber->amount : $request->PaidAmt;
            $Date = $request->TrxDtTm;
            $BillAccount = $request->AccNum;

            if (!$ControlNumber) {
                return response()->json([
                    'PymtReqId' => $BillPymtReqId,
                    'TrxId' => $BillTrxId,
                    'StatusCode' => '102',
                    'StatusDesc' => 'Control Number not found'
                ]);
            }

            if ($ControlNumber->status == 'PAID') {
                return response()->json([
                    'PymtReqId' => $BillPymtReqId,
                    'TrxId' => $BillTrxId,
                    'StatusCode' => '111',
                    'StatusDesc' => 'Bill already settled'
                ]);
            }

            if ($ControlNumber->payoption == 'EXACT') {
                if ($ControlNumber->amount != $PaidAmt) {
                    return response()->json([
                        'PymtReqId' => $BillPymtReqId,
                        'TrxId' => $BillTrxId,
                        'StatusCode' => '109',
                        'StatusDesc' => 'Paid amount is not exact billed amount'
                    ]);
                }
            } else {

                if ($ControlNumber->amount < $PaidAmt) {
                    return response()->json([
                        'PymtReqId' => $BillPymtReqId,
                        'TrxId' => $BillTrxId,
                        'StatusCode' => '109',
                        'StatusDesc' => 'Paid amount is not exact billed amount'
                    ]);
                }
            }

            //check payref if already used
            $payref = Payment::where('reference', $BillPayRefId)->first();
            if ($payref) {
                return response()->json([
                    'PymtReqId' => $BillPymtReqId,
                    'TrxId' => $BillTrxId,
                    'StatusCode' => '102',
                    'StatusDesc' => 'Payment reference number already paid'
                ]);
            }

            //Generate Receipt number
            $receipt = 'ZSH' . date('ymdhis') . rand(2, 2);


            DB::transaction(function () use ($BillCtrNum, $BillTrxId, $BillPayRefId, $BillPymtReqId, $PaidAmt, $Date, $request, $receipt) {
                //Update Control Number
                $ControlNumber = ControlNumber::where('control_number',$BillCtrNum)->first();

                if ($PaidAmt == $ControlNumber->amount) {
                    $status = 'PAID';
                } else {
                    $status = 'INCOMPLETE';
                }

                $ControlNumber->status = $status;
                $ControlNumber->save();

                //Add Payment Transaction
                $payment = new Payment();
                $payment->control_number_id = $ControlNumber->id;
                $payment->payment_date = date_format(date_create($Date), 'Y-m-d');
                $payment->payment_method = $request->has('isCash') ? 'BANK': 'BANK';
                $payment->amount = $PaidAmt;
                $payment->reference = $BillPayRefId;
                $payment->payment_request = $BillPymtReqId;
                $payment->receipt = $receipt;
                $payment->user_id = auth()->id() ?? 1;

                $payment->save();


            });

            if($ControlNumber->admission->phone != null) {

                //send SMS to customer
                $sms = "Umefanikiwa kulipa " . number_format($PaidAmt) . " ada ya maombi ya kujiunga na ZSH kwa jina " . $ControlNumber->admission->full_name . ". Kumb.".$receipt;

                //clean phone numbers
                $number = str_replace('-', '', $ControlNumber->admission->phone);
                $number = str_replace(' ', '', $number);

               // SmsJob::Dispatch($sms, $number);
            }

            //get result from NECTA
            getResultJob::dispatch($ControlNumber->admission);

            //extra index
             if($ControlNumber->admission->index_number_2){
                 getResultJob::dispatch($ControlNumber->admission,$ControlNumber->admission->index_number_2 );
             }

            if($ControlNumber->admission->index_number_3){
                getResultJob::dispatch($ControlNumber->admission,$ControlNumber->admission->index_number_3 );
            }

            if($request->has('isCash')){
                return redirect()->back();
            }

            return response()->json([
                'PymtReqId' => $BillPymtReqId,
                'TrxId' => $BillTrxId,
                'StatusCode' => '100',
                'StatusDesc' => 'Success',
                'ReceiptNum' => $receipt
            ]);


            //end of auth
        } else {
            return response()->json(
                [
                    'StatusCode' => '106',
                    'StatusDesc' => 'Unauthorized',
                ]);
        }
    }
}
