<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedCourse extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ["course_id", "admission_id", "priority", "admission_id"];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function admission(){
        return $this->belongsTo(Admission::class);
    }
}
