<?php

namespace App\Jobs;

use App\Action\FetchInformationAction;
use App\Action\ResultValidateAction;
use App\Models\Result;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class getResultJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private FetchInformationAction $fetchInformationAction;
    private ResultValidateAction $resultValidateAction;
    private $admission;
    /**
     * @var null
     */
    private $index_number;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($admission, $index_number = null)
    {
        $this->admission = $admission;
        $this->index_number = $index_number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FetchInformationAction $fetchInformationAction, ResultValidateAction $resultValidateAction)
    {

        if($this->index_number){
            $index = $this->index_number;
        }else{
            $index = $this->admission->index_number;
        }

        $index_number = explode('/', $index);

        $index = $index_number[0].'-'.$index_number[1];
        $year = $index_number[2];
        // Job hii inaitwa ndani ya payment callback (QUEUE_CONNECTION=sync), kwa hiyo
        // NECTA isipopatikana tuna-log tu; admin anaweza ku-reload result baadae.
        try {
            $results = $fetchInformationAction->ResultsHandle($index, $year);
        } catch (\App\Exceptions\ExternalServiceUnavailableException $e) {
            report($e);
            $results = false;
        }

        if($results){
            Result::updateOrCreate(
                [
                    'admission_id' =>  $this->admission->id,
                    'index_number' => $index
                ],
                [
                'admission_id' => $this->admission->id,
                'result_payload' => json_encode($results),
                'index_number' => $index,
                'status' => 'CREATED'
            ]);
        }

        //save applicable course to admission
        $this->admission->course_payload =  $resultValidateAction->handle($this->admission->id);
        $this->admission->save();
    }
}
