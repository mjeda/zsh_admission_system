<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCriteria extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ["title", "value", "course_id", "filter", "alternative_for"];

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
