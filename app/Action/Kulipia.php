<?php
/*
 *  Project Name: Kulipia.com
 *  Description: Functionalities for KulipiaAPI integration
 *  Auth: ZMOTION
 *  Version: 1.0 - 2023
 */

namespace App\Action;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Kulipia
{
    private $dev_endpoint = 'https://dev-api.kulipia.com/api/';
    private $live_endpoint = 'https://api.kulipia.com/api/';

    private $active_endpoint = null;
    public string $api_token = "85602abf726e421b28281fbc668259a2e4feade4f267c737e90ae048760c9fa9";
    public string $api_secret = "20iKzLlEeY9JU";
    public int $merchant_account = 9233;

    public function __construct(public bool $is_live = false)
    {
        $this->active_endpoint = $this->is_live ? $this->live_endpoint : $this->dev_endpoint;
    }

    /*
     *  Request Control Number / Payment Order
     */
    public function requestBill(array $payload)
    {
        $data = [
            "expire_date" => $payload['expire_date'],
            "bill_reference" => $payload['bill_reference'],
            "pay_option" => $payload['pay_option'],
            "bill_currency" => $payload['bill_currency'],
            "payer_name" => $payload['payer_name'],
            "payer_phone" => $payload['payer_phone'],
            "bill_amount" => $payload['bill_amount'],
            "bill_description" => $payload['bill_description'],
        ];

        $data['bill_items'][] = [
            'fs_code' => "FC923300001",
            'amount' => $payload['bill_amount'],
        ];

        //sign data with HMAC
        $signed_payload = $this->signPayload(json_encode($data));

        $headers = [
            'api-key' => $this->api_token,
            'api-secret' => $this->api_secret,
            'merchant-account' => $this->merchant_account
        ];

        $response = Http::withHeaders($headers)->post($this->active_endpoint.'bill/request', $signed_payload);

        if ($response->ok())
        {

            $responseData = json_decode($response->body(), true);
            if (isset($responseData['bill_number'])) {
                return $responseData['bill_number'];
            } else {
                return null;
            }
        } else {
            return null;
        }

    }


    /*
     *  Cancel Control Number / Payment Order
     */
    public function cancelBill(int $control_number, string $reason = 'None')
    {
        $data = [
            "merchant_account" => $this->merchant_account,
            "bill_number" => $control_number,
            "reason" => $reason
        ];

        //Sign with HMAC
        $signed_payload = $this->signPayload(json_encode($data));

        $headers = [
            'api-key' => $this->api_token,
            'api-secret' => $this->api_secret,
            'merchant-account' => $this->merchant_account
        ];

        $client = new GuzzleHttp\Client();
        $response = $client->request('POST', $this->active_endpoint.'bill/request', $signed_payload,
            ['headers' => $headers]);

        return $response->getBody();
    }

    /* Used internal, call them directly if you know what you are doing */

    public function verifyPayload(array $content): bool
    {
        $calculatedSignature = hash_hmac('sha256', $content['payload'], $this->api_secret, true);

        if (hash_equals($calculatedSignature, base64_decode($content['signature']))) {
            return true;
        }

        return false;
    }

    public function signPayload($payload): array
    {
        return
            [
                'payload' => $payload,
                'signature' => base64_encode(hash_hmac('sha256', $payload, $this->api_secret, true))
            ];
    }

    public function extendBill(string $control_number, string $new_expire_date)
    {
        $data = [
            "bill_number" => $control_number,
            "expire_date" => $new_expire_date,
        ];

        //Sign with HMAC
        $signed_payload = $this->signPayload(json_encode($data));

        $headers = [
            'api-key' => $this->api_token,
            'api-secret' => $this->api_secret,
            'merchant-account' => $this->merchant_account
        ];

        return Http::withHeaders($headers)->post($this->active_endpoint.'bill/extend', $signed_payload);
    }
}
