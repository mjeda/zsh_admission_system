<?php

namespace App\Action;

use App\Action\FetchInformationAction;
use App\Action\ResultValidateAction;
use App\Models\Admission;
use App\Models\Result;

class ReloadResult
{
    public function __construct(
        protected FetchInformationAction $fetchInformationAction,
        protected ResultValidateAction $resultValidateAction
    ) {}

    public function reload(Admission $admission, ?string $index_number = null)
    {
        $index = $index_number ?? $admission->index_number;

        $indexArr = explode('/', $index);
        $index = $indexArr[0] . '-' . $indexArr[1];
        $year  = $indexArr[2];

        $results = $this->fetchInformationAction
            ->ResultsHandle($index, $year);

        if ($results) {
            Result::updateOrCreate(
                [
                    'admission_id' => $admission->id,
                    'index_number' => $index
                ],
                [
                    'result_payload' => json_encode($results),
                    'status' => 'CREATED'
                ]
            );
        }

        $admission->course_payload =
            $this->resultValidateAction->handle($admission->id);

        $admission->save();
    }
}
