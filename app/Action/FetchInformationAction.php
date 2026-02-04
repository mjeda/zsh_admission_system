<?php


namespace App\Action;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class FetchInformationAction
{

    public function PersonalHandle($index, $year)
    {
        $data = [
            "index_number" => $index,
            "exam_year" => $year,
            "exam_id" => 1,
            "api_key" => config('app.necta_key')
        ];

        try {
            // Implement retry logic with a 120-second timeout
            $response = Http::retry(3, 100)->timeout(60)->withoutVerifying()->post('https://api.necta.go.tz/api/particulars/individual', $data);

            if ($response->successful()) {
                return json_decode($response->body());
            } else {
                Log::error('NECTA API returned an unsuccessful response.', ['response' => $response->body()]);
                return redirect()->route('error.page')->with('error', 'NECTA request failed.');
            }
        } catch (Exception $e) {
            Log::error('Error in NECTA API request', ['index' => $index, 'year' => $year, 'error' => $e->getMessage()]);

            if ($e instanceof \Illuminate\Http\Client\ConnectionException && $e->getCode() === 28) {
                return redirect()->route('error.page')->with('error', 'The NECTA request timed out.');
            }

            return redirect()->route('error.page')->with("error", "We're sorry, the NECTA server is currently busy and unable to retrieve your information. Please try again later.");
        }
    }


    public function ResultsHandle($index, $year)
    {
        $data = [
                "index_number" => $index,
                "exam_year" => $year,
                "exam_id" => 1,
                'api_key' => config('app.necta_key')
            ];

        Log::debug(json_encode($data));

        $response = Http::retry(3, 100)->timeout(60)->withoutVerifying()
            ->post('https://api.necta.go.tz/api/results/individual', $data);

        if ($response->ok()) {
            return json_decode($response->body());
        }

        return false;
    }

}