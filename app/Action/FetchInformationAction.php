<?php


namespace App\Action;


use App\Support\ExternalApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchInformationAction
{

    /**
     * Inarudisha taarifa za mwanafunzi, au null kama NECTA imejibu haikupata.
     *
     * @throws \App\Exceptions\ExternalServiceUnavailableException NECTA isipopatikana
     */
    public function PersonalHandle($index, $year)
    {
        $data = [
            "index_number" => $index,
            "exam_year" => $year,
            "exam_id" => 1,
            "api_key" => config('app.necta_key')
        ];

        $response = ExternalApi::call(ExternalApi::NECTA, 'kuhakiki taarifa za mwanafunzi (index number)', fn () =>
            Http::retry(3, 100)->timeout(60)->withoutVerifying()
                ->post('https://api.necta.go.tz/api/particulars/individual', $data)
        );

        if ($response->successful()) {
            return json_decode($response->body());
        }

        Log::error('NECTA API returned an unsuccessful response.', ['index' => $index, 'year' => $year, 'response' => $response->body()]);
        return null;
    }


    /**
     * @throws \App\Exceptions\ExternalServiceUnavailableException NECTA isipopatikana
     */
    public function ResultsHandle($index, $year)
    {
        $data = [
                "index_number" => $index,
                "exam_year" => $year,
                "exam_id" => 1,
                'api_key' => config('app.necta_key')
            ];

        $response = ExternalApi::call(ExternalApi::NECTA, 'kupakua matokeo ya mtihani', fn () =>
            Http::retry(3, 100)->timeout(60)->withoutVerifying()
                ->post('https://api.necta.go.tz/api/results/individual', $data)
        );

        if ($response->ok()) {
            return json_decode($response->body());
        }

        return false;
    }

}
