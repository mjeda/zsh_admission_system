<?php


namespace App\Action;


use App\Models\ControlNumber;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Jobs\getResultJob;

class ReceivePayment
{
    public static function handle(Request $request)
    {
        $payload = json_decode($request->payload);

        //init kulipia
        $kulipia = new Kulipia(true);

        //verify signature HMAC
        if (!$kulipia->verifyPayload(['payload' => $request->payload, 'signature' => $request->signature])) {
            return response()->json(['status' => 403, 'message' => 'Signature verification failed']);
        }

        //Verify Bill
        $bill = ControlNumber::where('control_number', $payload->bill_number)->first();

        if (!$bill) {
            return response()->json(['status' => 404, 'message' => 'Bill unknown']);
        }

        //Add Payment Transaction
        $payment = new Payment();
        $payment->control_number_id = $bill->id;
        $payment->payment_date = date_format(date_create($payload->payment_date), 'Y-m-d');
        $payment->payment_method = 'BANK';
        $payment->amount = $payload->amount;
        $payment->reference = $payload->transaction_reference;
        $payment->payment_request = $payload->transaction_id;
        $payment->receipt = $payload->receipt_number;
        $payment->user_id = auth()->id() ?? 1;

        $payment->save();

        $bill->status = "PAID";
        $bill->save();

        //get result from NECTA
        getResultJob::dispatch($bill->admission);

        //extra index
         if($bill->admission->index_number_2){
             getResultJob::dispatch($bill->admission,$bill->admission->index_number_2 );
         }

        if($bill->admission->index_number_3){
            getResultJob::dispatch($bill->admission,$bill->admission->index_number_3 );
        }

        return response()->json(['status' => 200, 'message' => 'Success']);
    }
}
