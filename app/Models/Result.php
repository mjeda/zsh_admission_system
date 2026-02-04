<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

//    protected $casts =[
//        'result_payload' => 'array'
//    ];

    protected $guarded = false;

    public function containSubject($code)
    {
        return collect($this->result_payload['subjects'])->where('subject_code', $code);
    }
}
