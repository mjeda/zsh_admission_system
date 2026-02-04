<?php


namespace App\Action;


use Illuminate\Support\Facades\Http;

class viewFeedbackErrorNACTE
{
    public function handle($program_id, $intake, $year){

        $response = Http::withoutVerifying()->get('https://www.nacte.go.tz/nacteapi/index.php/api/feedbackcorrection/'.$program_id->code.'-'.$year.'-'.$intake->title.'/HIe3c093414d9442.2e03ef78df72fba8728148bc1f44265b7728116e0d868db0b8795988373d0f7c.511ca4ac5243f8b3943734f371b4033a96bba239');


        if($response->ok()){
            return json_decode($response->body());
        }
    }
}
