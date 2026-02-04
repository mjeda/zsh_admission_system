<?php


namespace App\Action;


use Illuminate\Support\Facades\Http;

class viewNECTA
{
    public function handle($program_id, $intake, $year){

        $response = Http::withoutVerifying()->get('https://www.nacte.go.tz/nacteapi/index.php/api/pushedlist/'.$program_id->code.'-'.$year.'-'.$intake->title.'/ij90088cd114eee2.854cf48da1387b56462652cf01510865f4a7e42bfb6c93809620ca7f3b9af444.bcf2affde2156fe4a1259b080ab5a57f7ca62bbe');


        if($response->ok()){
            return json_decode($response->body());
        }
    }
}
