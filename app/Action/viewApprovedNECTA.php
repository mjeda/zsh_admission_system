<?php


namespace App\Action;


use App\Support\ExternalApi;
use Illuminate\Support\Facades\Http;

class viewApprovedNECTA
{
    public function handle($program_id, $intake, $year){

        $response = ExternalApi::call(ExternalApi::NACTE, 'kupata orodha ya wanafunzi waliohakikiwa', fn () =>
            Http::withoutVerifying()->timeout(60)->get('https://www.nacte.go.tz/nacteapi/index.php/api/verificationresults/'.$program_id->code.'-'.$year.'-'.$intake->title.'/IJb2050551c25507.63ec9ef07f8b1b43c2bd8ba52c7a70374549a8a31875d67dec3d530c2e1c99fb.08da9ac1a860ecb87036a09a9693aeb489094852')
        );

        if($response->ok()){
            return json_decode($response->body());
        }
    }
}
